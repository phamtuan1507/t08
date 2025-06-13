<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminCartControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);
    }

    public function testIndexReturnsViewWithPaginatedCarts()
    {
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

    public function testShowReturnsViewWithCartDetails()
    {
        $cart = Cart::factory()->create();
        CartItem::factory()->create(['cart_id' => $cart->id]);

        $response = $this->get(route('admin.carts.show', $cart));

        $response->assertStatus(200);
        $response->assertViewIs('admin.carts.show');
        $response->assertViewHas('cart');
        $this->assertEquals($cart->id, $response->viewData('cart')->id);
    }

    public function testDestroyCartSuccess()
    {
        $cart = Cart::factory()->create();
        CartItem::factory()->create(['cart_id' => $cart->id]);

        $response = $this->delete(route('admin.carts.destroy', $cart));

        $response->assertRedirect(route('admin.carts.index'));
        $response->assertSessionHas('success', 'Giỏ hàng đã được xóa.');
        $this->assertDatabaseMissing('carts', ['id' => $cart->id]);
        $this->assertDatabaseMissing('cart_items', ['cart_id' => $cart->id]);
    }

    public function testDestroyCartFailureReturnsError()
    {
        $cart = Cart::factory()->create();
        // Mock exception (cần cấu hình thêm nếu dùng mock trong Feature Test)
        // Ở đây giả lập bằng cách không xóa được (phụ thuộc vào cấu hình DB)
        $this->expectException(\Exception::class);
        $response = $this->delete(route('admin.carts.destroy', $cart));
        $response->assertSessionHas('error', 'Không thể xóa giỏ hàng.');
    }

    public function testDestroyItemSuccess()
    {
        $cartItem = CartItem::factory()->create();

        $response = $this->delete(route('admin.carts.destroyItem', $cartItem));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Sản phẩm đã được xóa khỏi giỏ hàng.');
        $this->assertDatabaseMissing('cart_items', ['id' => $cartItem->id]);
    }

    public function testDestroyItemFailureReturnsError()
    {
        $cartItem = CartItem::factory()->make(); // Không tạo trong DB để gây lỗi
        $response = $this->delete(route('admin.carts.destroyItem', $cartItem));

        $response->assertStatus(404); // Giả sử 404 vì không tìm thấy
        $this->assertSessionHas('error', 'Không thể xóa sản phẩm.');
    }
}
