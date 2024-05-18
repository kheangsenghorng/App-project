<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
           public function addToCart(Request $request) {
    $product = Product::with('product_images')->find($request->id);

    if ($product == null) {
        return response()->json([
            'status' => false,
            'message' => 'Record not found'
        ]);
    }

    // Check if the product already exists in the cart
    $cartItem = Cart::search(function ($cartItem, $rowId) use ($product) {
        return $cartItem->id === $product->id;
    })->first();

    if ($cartItem) {
        $status = false;
        $message = $product->title . ' already added in cart';
    } else {
        Cart::add($product->id, $product->title, 1, $product->price, [
            'productImage' => $product->product_images->first() ?? ''
        ]);

        $status = true;
        $message = $product->title . ' added in cart';
    }

    return response()->json([
        'status' => $status,
        'message' => $message
    ]);
}


        public function cart() {
            $cartContent = Cart::content();
            $data['cartContent'] = $cartContent;

            return view('front.cart', $data);
        }
        
        public function updateCart(Request $request){
            $rowId = $request->rowId;
            $qty = $request->qty;
             
            $iteminfo = Cart::get($rowId);

            //check qty aviblable in stock 
            $product = Product::find($iteminfo->id);

            if($product->track_qty == 'Yes'){
                 if($qty <= $product->qty) {
                    Cart::update($rowId, ['qty' => $qty]);
                    $message ='Cart updated successfully';
                    $status = true;
                    session()->flash('success', $message);
                 }else{
                     $message = 'Requested qty('.$qty.') not avaolale in stock.';
                     $status = false;
                     session()->flash('error', $message);
                 }
            }else{
                   Cart::update($rowId, ['qty' => $qty]);
                    $message ='Cart updated successfully';
                    $status = true;
                    session()->flash('success', $message);
            }

           
         
            return response()->json([
                'status'=> $status,
                'message' =>  $message
            ]);
        }

        public function deleteItem(Request $request){

           $iteminfo = Cart::get($request->rowId);
           if($iteminfo == null){
               $errorMessage = 'Item not found in cart';
                session()->flash('error',$errorMessage);
                return response()->json([

                'status'=> false,
                'message' =>  $errorMessage
            ]);
           }
           
           Cart::remove($request->rowId);
           $message = 'Item removed from cart successfully';
           session()->flash('success', $message);
            return response()->json([

                'status'=> true,
                'message' =>  $message
            ]);
            
        }
}
