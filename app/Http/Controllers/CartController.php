<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Support\Facades\DB;
class CartController extends Controller
{
    public function index()
    {
        $cartItems = Auth::user()->cartItems()->with('product')->get();
        return response()->json($cartItems);
    }
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);
        
        $productId = $request->input('product_id');
        $variantId = $request->input('variant_id');
        $quantity = $request->input('quantity');
        $user = Auth::user();

        // Xây dựng câu truy vấn để tìm item đã tồn tại
        $query = CartItem::where('user_id', $user->id)->where('product_id', $productId);

        if ($variantId) {
            // Nếu có biến thể, phải tìm chính xác biến thể đó
            $query->where('product_variant_id', $variantId);
        } else {
            // Nếu không, đảm bảo chỉ tìm sản phẩm không có biến thể
            $query->whereNull('product_variant_id');
        }

        $cartItem = $query->first();

        if ($cartItem) {
            // Nếu item đã tồn tại, chỉ cần cộng thêm số lượng
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            // Nếu chưa, tạo mới cart item
            CartItem::create([
                'user_id' => $user->id,
                'product_id' => $productId,
                'product_variant_id' => $variantId, // Lưu ID của biến thể vào đây
                'quantity' => $quantity,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Sản phẩm đã được thêm vào giỏ hàng!',
            'cart'    => $this->getCartDataForAPI()
        ]);
    }
    public function update(Request $request, $cartItemId)
    {
        $request->validate(['quantity' => 'required|integer|min:0']);
        $cartItem = CartItem::where('id', $cartItemId)->where('user_id', Auth::id())->firstOrFail();
        if ($request->quantity == 0) {
            $cartItem->delete();
            $message = 'Sản phẩm đã được xóa khỏi giỏ!';
        } else {
            $cartItem->update(['quantity' => $request->quantity]);
            $message = 'Giỏ hàng đã được cập nhật!';
        }
        return response()->json(['message' => $message, 'cart' => $this->getCartData()]);
    }
    public function remove($cartItemId)
    {
        CartItem::where('id', $cartItemId)->where('user_id', Auth::id())->firstOrFail()->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Sản phẩm đã được xóa khỏi giỏ!',
            'cart' => $this->getCartDataForAPI()
        ]);
    }
    private function getCartData()
    {
        return Auth::user()->cartItems()->count();
    }
    public function showCartPage()
    {
    // Dù có check Auth hay không, chúng ta luôn cần biến $cartItems trong view.
        $cartItems = []; 
        
    // Nếu người dùng đã đăng nhập, lấy dữ liệu từ database.
        if (Auth::check()) {
        // Dùng lại hàm getCartDataForAPI() để có cấu trúc dữ liệu đồng nhất
        // và đảm bảo có đủ thông tin (product, total_price, etc.)
        // Lấy mảng 'items' từ kết quả trả về.
            $cartData = $this->getCartDataForAPI();
            $cartItems = $cartData['items']; 
        }
        
    // Truyền biến $cartItems vào view.
        return view('cart.index', ['cartItems' => $cartItems]);
    }
    public function buyNow(Request $request)
    {
        $data = $request->validate([
            'product_id'    => ['required','integer', /* Rule::exists('products','id') */],
            'variant_id'    => ['nullable','integer', /* Rule::exists('product_variants','id') */],
            'quantity'      => ['required','integer','min:1'],
            'variant_text'  => ['nullable','string','max:255'],
        ]);

        $productId   = (int) $data['product_id'];
        $variantId   = $data['variant_id'] ? (int) $data['variant_id'] : null;
        $qty         = (int) $data['quantity'];
        $variantText = $data['variant_text'] ?? null;

        // (Khuyến nghị) Nếu có Model, kiểm tra biến thể thuộc đúng product:
        if ($variantId) {
            $ok = \App\Models\ProductVariant::where('id', $variantId)
                    ->where('product_id', $productId)
                    ->exists();
            if (!$ok) {
                return back()->withErrors(['variant_id' => 'Biến thể không hợp lệ với sản phẩm này.']);
            }
        }

        if (Auth::check()) {
            // --- USER ĐĂNG NHẬP ---
            $user = Auth::user();

            // Upsert theo cặp (product_id, variant_id)
            $item = $user->cartItems()->firstOrNew([
                'product_id' => $productId,
                'variant_id' => $variantId, // nullable OK
            ]);

            $currentQty = max(0, (int)($item->quantity ?? 0));
            $item->quantity = $currentQty + $qty;

            // Nếu bảng cart_items có cột variant_text (tuỳ DB của anh)
            if (schema_has_column('cart_items', 'variant_text') && $variantText !== null) {
                $item->variant_text = $variantText;
            }

            $item->save();

            // (Tuỳ chọn) set "đã chọn để checkout" nếu anh muốn checkout chỉ lấy item vừa bấm:
            // session(['checkout_selection' => [ ['product_id'=>$productId,'variant_id'=>$variantId] ]]);

        } else {
            // --- KHÁCH (GUEST) ---
            // session('guest_cart') lưu MẢNG item với cặp khóa (product_id, variant_id)
            // Cấu trúc mỗi item: ['product_id'=>int, 'variant_id'=>int|null, 'quantity'=>int, 'variant_text'=>?]
            $cart = session()->get('guest_cart', []);

            $foundIndex = null;
            foreach ($cart as $i => $it) {
                $pid = (int)($it['product_id'] ?? 0);
                $vid = array_key_exists('variant_id', $it) ? ($it['variant_id'] === null ? null : (int)$it['variant_id']) : null;
                if ($pid === $productId && $vid === $variantId) {
                    $foundIndex = $i;
                    break;
                }
            }

            if ($foundIndex !== null) {
                // Cộng dồn đúng dòng biến thể
                $cart[$foundIndex]['quantity'] = max(0, (int)$cart[$foundIndex]['quantity']) + $qty;
                if ($variantText !== null) {
                    $cart[$foundIndex]['variant_text'] = $variantText;
                }
            } else {
                $cart[] = [
                    'product_id'   => $productId,
                    'variant_id'   => $variantId,     // có thể null
                    'quantity'     => $qty,
                    'variant_text' => $variantText,   // nếu muốn show tại checkout
                ];
            }

            session(['guest_cart' => $cart]);

            // (Tuỳ chọn) nếu muốn checkout chỉ item vừa bấm:
            // session(['checkout_selection' => [ ['product_id'=>$productId,'variant_id'=>$variantId] ]]);
        }

        return redirect()->route('checkout.index');
    }

    private function getCartDataForAPI()
    {
        $user = auth()->user();
        if (!$user) {
            return ['items' => [], 'total_price' => 0, 'total_quantity' => 0];
        }

        // Tải sẵn cả product và variant để tối ưu
        $cartItems = CartItem::where('user_id', $user->id)->with('product', 'variant.attributeValues.attribute')->get();
        
        $total_price = 0;
        $total_quantity = 0;

        // Xử lý lại dữ liệu để frontend luôn nhận được cấu trúc đồng nhất
        $items = $cartItems->map(function($item) use (&$total_price, &$total_quantity) {
            $itemData = $item->variant ?: $item->product; // Ưu tiên lấy dữ liệu từ biến thể
            
            $name = $item->product->name;
            // Nếu là biến thể, thêm tên thuộc tính vào (VD: (Màu: Đỏ, Size: L))
            if ($item->variant) {
                $options = $item->variant->attributeValues->map(fn($v) => $v->attribute->name . ': ' . $v->value)->implode(', ');
                $name .= " ($options)";
            }

            $price = $item->variant ? $item->variant->price : $item->product->price;
            $image = $item->variant && $item->variant->image ? $item->variant->image : $item->product->image;

            $total_price += $item->quantity * $price;
            $total_quantity += $item->quantity;

            // Trả về một cấu trúc chuẩn cho frontend
            return [
                'id' => $item->id, // ID của cart_item để xóa
                'product_id' => $item->product->id,
                'variant_id' => $item->variant->id ?? null,
                'name' => $name,
                'price' => $price,
                'quantity' => $item->quantity,
                'image' => asset($image),
                'slug' => $item->product->slug->slug,
            ];
        });

        return [
            'items'          => $items,
            'total_price'    => $total_price,
            'total_quantity' => $total_quantity,
        ];
    }

    public function merge(Request $request)
    {
        $guestCart = $request->input('guest_cart', []);
        $user = auth()->user();

        if ($user && !empty($guestCart)) {
            foreach ($guestCart as $guestItem) {
                $existingItem = CartItem::where('user_id', $user->id)
                ->where('product_id', $guestItem['id'])
                ->first();

                if ($existingItem) {
                    $existingItem->quantity += $guestItem['quantity'];
                    $existingItem->save();
                } else {
                    CartItem::create([
                        'user_id' => $user->id,
                        'product_id' => $guestItem['id'],
                        'quantity' => $guestItem['quantity'],
                    ]);
                }
            }
        }

        return response()->json(['success' => true, 'message' => 'Giỏ hàng đã được gộp.']);
    }
}