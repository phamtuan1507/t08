<?php

namespace Tests\Unit;

use App\Http\Controllers\AdminCartController;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Mockery;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminCartControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new AdminCartController();
        $this->actingAs(User::factory()->create(['role' => 'admin']));
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    protected function createCartWithItems($user, $itemCount = 1)
    {
        $cart = Cart::create(['user_id' => $user->id]);

        // Tạo một category thủ công nếu chưa có
        $category = Category::firstOrCreate(['name' => 'Test Category']);

        for ($i = 0; $i < $itemCount; $i++) {
            $product = Product::create([
                'name' => 'Test Product ' . $i,
                'price' => 50.00,
                'description' => 'Test description',
                'category_id' => $category->id, // Sử dụng id của category đã tạo
                'image' => 'test-image.jpg',
                'stock' => 100,
            ]);
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => 1,
                'price' => 30.00,
            ]);
        }
        return $cart;
    }
    public function testIndexReturnsViewWithPaginatedCarts()
    {
        // Tạo dữ liệu test thủ công
        $user = User::factory()->create();
        for ($i = 0; $i < 15; $i++) {
            Cart::create(['user_id' => $user->id]);
        }

        $response = $this->controller->index();

        $carts = $response->getData()['carts'];

        $this->assertEquals('admin.carts.index', $response->name());
        $this->assertArrayHasKey('carts', $response->getData());
        $this->assertTrue($carts->perPage() <= 15); // Kiểm tra số lượng mỗi trang
        $this->assertEquals(15, $carts->total()); // Tổng số giỏ hàng
    }

    public function testShowLoadsRelationshipsAndReturnsView()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'), // Mã hóa password
            'role' => 'admin', // Nếu có cột role
        ]);
        $cart = $this->createCartWithItems($user, 1);
        $response = $this->controller->show($cart);

        $this->assertEquals('admin.carts.show', $response->name());
        $this->assertArrayHasKey('cart', $response->getData());
        $this->assertEquals($cart->id, $response->getData()['cart']->id);
        $this->assertNotNull($response->getData()['cart']->user);
        $this->assertNotEmpty($response->getData()['cart']->items);
    }

    public function testDestroySuccess()
    {
// Tạo dữ liệu test thủ công cho User
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

// Tạo dữ liệu test thủ công cho Cart
        $cart = Cart::create([
            'user_id' => $user->id,
        ]);

// Tạo một category thủ công nếu chưa có
        $category = Category::firstOrCreate(['name' => 'Test Category']);

// Tạo dữ liệu test thủ công cho Product
        $product = Product::create([
            'name' => 'Test Product',
            'price' => 50.00,
            'description' => 'Test description',
            'category_id' => $category->id, // Sử dụng id của category đã tạo
            'image' => 'test-image.jpg',
            'stock' => 100,
        ]);

        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => 30.00,
        ]);

        // $response = $this->controller->destroy($cart);

        // Sử dụng HTTP test với actingAs để mô phỏng người dùng đã đăng nhập
        $this->actingAs($user);
        $response = $this->delete(route('admin.carts.destroy', $cart));

        $this->assertDatabaseMissing('carts', ['id' => $cart->id]);
        $this->assertDatabaseMissing('cart_items', ['cart_id' => $cart->id]);
        $this->assertEquals(route('admin.carts.index'), $response->getTargetUrl());
        $this->assertTooManyRequests('success', 'Giỏ hàng đã được xóa.');
    }

    public function testDestroyFailureLogsError()
    {
        // Tạo dữ liệu test thủ công
        $user = User::factory()->create();
        $cart = Cart::create(['user_id' => $user->id]);
        // Mock exception
        Cart::shouldReceive('findOrFail')->with($cart->id)->andThrow(new \Exception('Database error'));
        Log::shouldReceive('error')->once();

        $response = $this->controller->destroy($cart);

        $this->assertSessionHas('error', 'Không thể xóa giỏ hàng.');
        $this->assertEquals(url()->previous(), $response->getTargetUrl());
    }

    public function testDestroyItemSuccess()
    {
        // Tạo dữ liệu test thủ công
        $user = User::factory()->create();
        $cart = Cart::create(['user_id' => $user->id]);
        $product = Product::factory()->create();
        $cartItem = CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 40.00,
        ]);

        $response = $this->controller->destroyItem($cartItem);

        $this->assertDatabaseMissing('cart_items', ['id' => $cartItem->id]);
        $this->assertEquals(url()->previous(), $response->getTargetUrl());
        $this->assertEquals('success', 'Sản phẩm đã được xóa khỏi giỏ hàng.');
    }

    public function testDestroyItemFailureLogsError()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'), // Mã hóa password
            'role' => 'admin', // Nếu có cột role
        ]);
        $cart = Cart::create(['user_id' => $user->id]);
        $product = Product::factory()->create();
        $cartItem = CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => 20.00,
        ]);

        // Mock lỗi bằng cách giả lập exception
        CartItem::shouldReceive('findOrFail')->with($cartItem->id)->andThrow(new \Exception('Database error'));
        Log::shouldReceive('error')->once();

        $response = $this->controller->destroyItem($cartItem);

        $this->assertSessionHas('error', 'Không thể xóa sản phẩm.');
        $this->assertEquals(url()->previous(), $response->getTargetUrl());
    }
}
