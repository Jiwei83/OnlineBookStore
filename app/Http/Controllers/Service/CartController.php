<?php
/**
 * Created by PhpStorm.
 * User: majiwei
 * Date: 26/08/2017
 * Time: 12:19 PM
 */
namespace App\Http\Controllers\Service;

use App\Entity\CartItem;
use App\Http\Controllers\Controller;
use App\Models\M3Result;
use Illuminate\Http\Request;

class CartController extends Controller {
    public function addCart(Request $request, $product_id) {

        $bk_cart = $request->cookie('bk_cart');
        $bk_cart_arr = ($bk_cart!=null ? explode(',', $bk_cart) : array());
        //如果用户已登录
        $member = $request->session()->get('member', '');
        if($member != '') {
            $cart_items = CartItem::where('member_id', $member->id)->get();

            $exist = false;
            foreach($cart_items as $cart_item) {
                if($product_id == $cart_item->product_id) {
                    $cart_item->count++;
                    $cart_item->save();
                    $exist = true;
                    break;
                }
            }

            if($exist == false) {
                $temp_item = new CartItem;
                $temp_item->member_id = $member->id;
                $temp_item->product_id = $product_id;
                $temp_item->count = 1;
                $temp_item->save();
            }

            $m3_result = new M3Result;
            $m3_result->status = 0;
            $m3_result->message = 'Add Succeeds';
            return response($m3_result->toJson())->withCookie('bk_cart', implode(',', $bk_cart_arr));
        }
        $count = 1;
        foreach ($bk_cart_arr as &$value) { //&为添加引用即可改变数组内的值
            $index = strpos($value, ':');
            if(substr($value, 0, $index) == $product_id) {
                $count = (int)substr($value, $index+1) + 1;
                $value = $product_id . ':' . $count;
                break;
            }
        }

        if($count == 1) {
            array_push($bk_cart_arr, $product_id . ':' . $count);
        }

        $m3_result = new M3Result;
        $m3_result->status = 0;
        $m3_result->message = 'Add Succeeds';
        return response($m3_result->toJson())->withCookie('bk_cart', implode(',', $bk_cart_arr));


    }

    public function deleteCart(Request $request) {
        $m3_result = new M3Result;
        $m3_result->status = 0;
        $m3_result->message = '删除成功';

        $product_ids = $request->input('product_ids', '');
        if($product_ids == '') {
            $m3_result->status = 1;
            $m3_result->message = '产品ID为空';
            return $m3_result->toJson();
        }
        $member = $request->session()->get('member', '');
        $product_ids_arr = explode(',', $product_ids);
        $bk_cart = $request->cookie('bk_cart');
        $bk_cart_arr = ($bk_cart!=null ? explode(',', $bk_cart) : array());
        if($member != '') {
            // 已登录
            CartItem::whereIn('product_id', $product_ids_arr)->delete();
            return $m3_result->toJson();
        }

        foreach ($bk_cart_arr as $key => $value) { //&为添加引用即可改变数组内的值
            $index = strpos($value, ':');
            $product_id = substr($value, 0, $index);
            //产品ID存在则删除该产品
            if(in_array($product_id, $product_ids_arr)) {
                array_splice($bk_cart_arr, $key, 1);
                continue;
            }
        }

        return response($m3_result->toJson())->withCookie('bk_cart', implode(',', $bk_cart_arr));
    }

}




















