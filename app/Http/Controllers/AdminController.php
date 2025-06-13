<?php

namespace App\Http\Controllers;

use App\Mail\ContactResponse;
use App\Models\Contact;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $userCount = User::count();
        $productCount = Product::count();
        $contacts = Contact::orderBy('created_at', 'desc')->get();

        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfDay());

        $dailyRevenue = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as revenue'))
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => $item->date,
                    'revenue' => $item->revenue ?? 0,
                ];
            });

        // Kiểm tra và gỡ lỗi
        if ($dailyRevenue->isEmpty()) {
            Log::warning('No revenue data found for date range', ['start' => $startDate, 'end' => $endDate]);
        }

        // Sản phẩm bán chạy
        $topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status', 'completed')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->groupBy('product_id')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                $product = Product::find($item->product_id);
                return [
                    'name' => $product->name,
                    'total_sold' => $item->total_sold,
                ];
            });

        return view('admin.dashboard', compact('userCount', 'productCount', 'contacts', 'dailyRevenue', 'startDate', 'endDate', 'topProducts'));

    }

    public function getRevenueData(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfDay());
        $dailyRevenue = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as revenue'))
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => $item->date,
                    'revenue' => $item->revenue ?? 0,
                ];
            });
        return response()->json($dailyRevenue);
    }

    public function statistic(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfDay());

        // Doanh thu
        $revenue = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total');

        // Sản phẩm bán chạy
        $topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status', 'completed')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->groupBy('product_id')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                $product = Product::find($item->product_id);
                return [
                    'name' => $product->name,
                    'total_sold' => $item->total_sold,
                ];
            });

        // Hành vi khách hàng
        $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])->count();
        $completedOrders = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $newCustomers = User::whereBetween('created_at', [$startDate, $endDate])->count();
        $completionRate = $totalOrders > 0 ? ($completedOrders / $totalOrders) * 100 : 0;

        // Dữ liệu cho biểu đồ doanh thu theo ngày
        $dailyRevenue = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as revenue'))
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => $item->date,
                    'revenue' => $item->revenue,
                ];
            });

        return view('admin.statistic.index', compact(
            'revenue',
            'topProducts',
            'totalOrders',
            'completedOrders',
            'newCustomers',
            'completionRate',
            'dailyRevenue',
            'startDate',
            'endDate'
        ));

        // return view('admin.statistic.index', compact('todayRevenue' ,'topProduct'));
    }

    public function detail_contact()
    {
        $contacts = Contact::orderBy('created_at', 'desc')->get();
        return view('admin.contact.index', compact('contacts'));
    }

    public function sendResponse(Request $request, $contactId)
    {
        $contact = Contact::findOrFail($contactId);

        $validated = $request->validate([
            'response' => 'required|string|max:1000',
        ]);

        Mail::to($contact->email)->send(new ContactResponse($contact, $validated['response']));

        Log::info('Contact response sent', ['contact_id' => $contactId, 'email' => $contact->email, 'response' => $validated['response']]);

        return redirect()->back()->with('success', 'Phản hồi đã được gửi thành công đến ' . $contact->email);
    }

    public function showChart()
    {
        $start = Carbon::parse(User::min("created_at")) ?? Carbon::now()->subYear();
        $end = Carbon::now();
        $period = CarbonPeriod::create($start, "1 month", $end);

        $usersPerMonth = collect($period)->map(function ($date) {
            $endDate = $date->copy()->endOfMonth();
            return [
                "count" => User::where("created_at", "<=", $endDate)->count(),
                "month" => $endDate->format("Y-m"),
            ];
        })->values();

        $data = $usersPerMonth->pluck("count")->toArray();
        $labels = $usersPerMonth->pluck("month")->toArray();

        if (empty($data) || empty($labels)) {
            $data = [0];
            $labels = [Carbon::now()->format("Y-m")];
        }

        $chart = Chartjs::build()
            ->name("UserRegistrationsChart")
            ->type("line")
            ->size(["width" => 400, "height" => 200])
            ->labels($labels)
            ->datasets([
                [
                    "label" => "User Registrations",
                    "backgroundColor" => "rgba(38, 185, 154, 0.31)",
                    "borderColor" => "rgba(38, 185, 154, 0.7)",
                    "data" => $data,
                ],
            ])
            ->options([
                'scales' => [
                    'x' => [
                        'type' => 'time',
                        'time' => [
                            'unit' => 'month',
                            'displayFormats' => ['month' => 'MMM YYYY'],
                        ],
                        'min' => $start->toDateString(),
                    ],
                    'y' => [
                        'beginAtZero' => true,
                    ],
                ],
                'plugins' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Monthly User Registrations',
                    ],
                    'legend' => [
                        'display' => true,
                        'position' => 'top',
                    ],
                ],
            ]);

        return view("admin.statistic.index", compact("chart"));
    }
}
