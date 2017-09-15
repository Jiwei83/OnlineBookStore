<?php
/**
 * Created by PhpStorm.
 * User: majiwei
 * Date: 7/09/2017
 * Time: 12:18 PM
 */

namespace App\Http\Controllers\View;

use App\Entity\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Entity\CartItem;

class CartController extends Controller{
    /**
     * 将cookie与数据库中的用户订单信息整合后传入cart模板
     *
     * @param Request $request
     * @return $this
     */
    public function toCart(Request $request) {
        $bk_cart = $request->cookie('bk_cart');
        $bk_cart_arr = ($bk_cart!=null ? explode(',', $bk_cart) : array());
        $cart_items = array();

        $member = $request->session()->get('member', '');
        //如果用户登录则将数据库中的产品订单与cookie中的产品订单整合后传给cart模板
        if($member != '') {
            $cart_items = $this->syncCart($member->id, $bk_cart_arr);
            return response()->view('cart', ['cart_items' => $cart_items])->withCookie('bk_cart', null);
        }

        foreach ($bk_cart_arr as $key => $value) {
            $index = strpos($value, ':');

            $cart_item = new CartItem();
            $cart_item->id = $key;
            $cart_item->product_id = substr($value, 0, $index);
            $cart_item->count = (int)substr($value, $index+1);
            $cart_item->product = Product::find($cart_item->product_id);
            if($cart_item->product != null) {
                array_push($cart_items, $cart_item);
            }
        }

        return view('cart')->with('cart_items', $cart_items);
    }


    /**
     * 同步cookie与数据库中的用户订单信息
     *
     * @param $member_id
     * @param $bk_cart_arr
     * @return array
     */
    private function syncCart($member_id, $bk_cart_arr) {
        $cart_items = CartItem::where('member_id', $member_id)->get();

        $cart_items_arr = array();
        foreach($bk_cart_arr as $value) {
            $index = strpos($value, ':');
            $product_id = substr($value, 0, $index);
            $count = (int)substr($value, $index+1);

            //判断离线购物车中product_id是否存在数据库中
            $exist = false;
            foreach($cart_items as $temp) {
                //如果存在则把最大的数量存入数据库中并把exist置为true跳出循环
                if($temp->product_id == $product_id) {
                    if($temp->count < $count) {
                        $temp->count = $count;
                        $temp->save();
                    }
                    $exist = true;
                    break;
                }
            }

            //不存在则加入数据库中并将产品添加到购物车产品数组
            if($exist == false) {
                $cart_item = new CartItem;
                $cart_item->member_id = $member_id;
                $cart_item->product_id = $product_id;
                $cart_item->count = $count;
                $cart_item->save();
                array_push($cart_items_arr, $cart_item);
            }
        }

        //为每个对象附加产品对象便于显示
        foreach($cart_items as $cart_item) {
            $cart_item->product = Product::find($cart_item->product_id);
            array_push($cart_items_arr, $cart_item);
        }

        return $cart_items_arr;
    }
}





































