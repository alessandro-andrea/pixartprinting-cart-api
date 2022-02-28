<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Routing\Controller as BaseController;

class CartController extends BaseController
{
    /**
     * Return the cart for the given $ecommerce_id and $customer_id
     *
     * @param int $ecommerce_id
     * @param int $customer_id
     * @return JsonResponse
     */
    public function showOne(int $ecommerce_id, int $customer_id)
    {
        $cart = Cart::with('itemList')
            ->where('ecommerce_id', '=', $ecommerce_id)
            ->where('customer_id', '=', $customer_id)
            ->orderBy('updated_at', 'desc')
            ->first();

        if (!is_null($cart)) {
            return response()->json($cart->toArray());
        } else {
            return response()->json([], 404);
        }
    }

    /**
     * Create e new cart
     *
     * @return JsonResponse
     */
    public function create(Request $request)
    {
        //region Input validation
        $validator = Validator::make($request->all(), [
            'ecommerce_id' => 'integer|min:1',
            'customer_id' => 'integer|min:1'
        ]);
        //endregion

        if (!$validator->fails()) {
            $ecommerce_id = $request->get('ecommerce_id');
            $customer_id = $request->get('customer_id');

            $cart = Cart::where('ecommerce_id', '=', $ecommerce_id)
                ->where('customer_id', '=', $customer_id)
                ->whereIn('status', ['created', 'building'])
                ->count();

            if ($cart == 0) {
                Cart::create([
                    'ecommerce_id' => $ecommerce_id,
                    'customer_id' => $customer_id,
                ]);
                return response()->json([], 201);
            } else {
                return response()->json([
                    'error_msg' => 'A cart is already created for $ecommerce_id=' . $ecommerce_id . ' and $customer_id=' . $customer_id
                ], 400);
            }
        } else {
            return response()->json([
                'error_msg' => implode('|', $validator->getMessageBag()->all())
            ], 400);
        }
    }

    /**
     * Update the cart for the given $ecommerce_id and $customer_id
     *
     * @param int $ecommerce_id
     * @param int $customer_id
     * @return JsonResponse
     */
    public function update(int $ecommerce_id, int $customer_id, Request $request)
    {
        //region Input validation
        $validator = Validator::make($request->all(), [
            'item_list' => 'array|min:1',
            'item_list.*.product_sku' => 'required|string|max:256',
            'item_list.*.product_name' => 'required|string|max:256',
            'item_list.*.file_type' => 'required|string|max:256',
            'item_list.*.quantity' => 'required|integer|min:1',
            'item_list.*.delivery_date' => 'required|date_format:Y-m-d|after_or_equal:tomorrow|before_or_equal:+1 week',
        ]);
        //endregion

        if (!$validator->fails()) {
            $cart = Cart::with('itemList')
                ->where('ecommerce_id', '=', $ecommerce_id)
                ->where('customer_id', '=', $customer_id)
                ->whereIn('status', ['created', 'building'])
                ->first();

            if (!is_null($cart)) {
                CartItem::where('cart_id', '=', $cart->id)->delete();

                //region Item price calculation
                $cart_price = 0;
                foreach ($request->get('item_list') as $item) {
                    $cart_item = new CartItem();
                    $cart_item->cart_id = $cart->id;
                    $cart_item->product_sku = $item['product_sku'];
                    $cart_item->product_name = $item['product_name'];
                    $cart_item->file_type = $item['file_type'];
                    $cart_item->quantity = $item['quantity'];
                    $cart_item->delivery_date = $item['delivery_date'];

                    // Standard base price of 1,00€ per item
                    $item_unit_price = 100;
                    switch (strtolower($cart_item->file_type)) {
                        case 'pdf':
                            // PDF +15%
                            $item_unit_price += $item_unit_price * 0.15;
                            break;
                        case 'psd':
                            // PSD +35%
                            $item_unit_price += $item_unit_price * 0.35;
                            break;
                        case 'ai':
                            // AI +25%
                            $item_unit_price += $item_unit_price * 0.25;
                            break;
                    }

                    $cart_item_price = $item_unit_price * $cart_item->quantity;

                    // Multiple items of same product_sku (no discount for quantities until 100)
                    if ($cart_item->quantity > 1000) {
                        // 20% discount for quantities above 1000
                        $cart_item_price -= $cart_item_price * 0.2;
                    } else if ($cart_item->quantity > 500) {
                        // 15% discount for quantities above 500
                        $cart_item_price -= $cart_item_price * 0.15;
                    } else if ($cart_item->quantity > 250) {
                        // 10% discount for quantities above 250
                        $cart_item_price -= $cart_item_price * 0.1;
                    } else if ($cart_item->quantity > 100) {
                        // 5% discount for quantities above 100
                        $cart_item_price -= $cart_item_price * 0.05;
                    }

                    $cart_item->price = $cart_item_price;
                    $cart_item->save();

                    $cart_price += $cart_item_price;
                }
                //endregion

                if ($cart->status == 'created') {
                    $cart->status = 'building';
                }
                $cart->price = $cart_price;
                $cart->save();

                return response()->json([]);
            } else {
                return response()->json([
                    'error_msg' => 'Cart not found for $ecommerce_id=' . $ecommerce_id . ' and $customer_id=' . $customer_id
                ], 400);
            }
        } else {
            return response()->json([
                'error_msg' => implode('|', $validator->getMessageBag()->all())
            ], 400);
        }
    }

    /**
     * Delete the cart for the given $ecommerce_id and $customer_id
     *
     * @param int $ecommerce_id
     * @param int $customer_id
     * @return JsonResponse
     */
    public function delete(int $ecommerce_id, int $customer_id)
    {
        $cart = Cart::where('ecommerce_id', '=', $ecommerce_id)
            ->where('customer_id', '=', $customer_id)
            ->whereIn('status', ['created', 'building'])
            ->first();

        if (!is_null($cart)) {
            CartItem::where('cart_id', '=', $cart->id)->delete();
            $cart->delete();
        }

        return response()->json([]);
    }

    /**
     * Checkout the cart for the given $ecommerce_id and $customer_id
     *
     * @param int $ecommerce_id
     * @param int $customer_id
     * @return JsonResponse
     */
    public function checkout(int $ecommerce_id, int $customer_id)
    {
        $cart = Cart::with('itemList')
            ->where('ecommerce_id', '=', $ecommerce_id)
            ->where('customer_id', '=', $customer_id)
            ->where('status', '=', 'building')
            ->first();

        if (!is_null($cart)) {
            if (!empty($cart->itemList)) {
                $cart->date_checkout = date('Y-m-d');

                //region Cart total price calculation
                $cart_price = 0;
                foreach ($cart->itemList as $cart_item) {
                    $cart_item_price = $cart_item->getRawOriginal('price');

                    // Comparing delivery date to date checkout (delivery date within 1 week of date checkout +0%)
                    $checkout_date = new \DateTime($cart->date_checkout);
                    $delivery_date = new \DateTime($cart_item->delivery_date);
                    $interval = $checkout_date->diff($delivery_date);

                    if ($interval->days <= 1) {
                        // Delivery date within 24h of date checkout +30%
                        $cart_item_price += $cart_item_price * 0.3;
                    } else if ($interval->days <= 2) {
                        // Delivery date within 48h of date checkout +20%
                        $cart_item_price += $cart_item_price * 0.2;
                    } else if ($interval->days <= 3) {
                        // Delivery date within 72h of date checkout +10%
                        $cart_item_price += $cart_item_price * 0.1;
                    }

                    $cart_item->price = $cart_item_price;
                    $cart_item->save();

                    $cart_price += $cart_item_price;
                }
                //endregion

                $cart->status = 'checkout';
                $cart->price = $cart_price;
                if ($cart->save()) {
                    return response()->json([]);
                } else {
                    return response()->json([
                        'error_msg' => 'An error is occurring during cart checkout.'
                    ], 400);
                }
            } else {
                return response()->json([
                    'error_msg' => 'Cart is empty for $ecommerce_id=' . $ecommerce_id . ' and $customer_id=' . $customer_id
                ], 400);
            }
        } else {
            return response()->json([
                'error_msg' => 'Cart not found for $ecommerce_id=' . $ecommerce_id . ' and $customer_id=' . $customer_id
            ], 401);
        }
    }
}
