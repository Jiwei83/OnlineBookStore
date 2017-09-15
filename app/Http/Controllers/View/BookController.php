<?php
/**
 * Created by PhpStorm.
 * User: majiwei
 * Date: 26/08/2017
 * Time: 12:19 PM
 */
namespace App\Http\Controllers\View;

use App\Http\Controllers\Controller;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\PdtContent;
use App\Entity\PdtImages;
use App\Entity\CartItem;
use Illuminate\Http\Request;

class BookController extends Controller {
    public function toCategory() {
        $categories = Category::whereNull('parent_id')->get();
        return view('category')->with('categories', $categories);
    }

    public function toProduct($category_id) {
        $products = Product::where('category_id', $category_id)->get();
        return view('product')->with('products', $products);
    }

    public function toPdtContent(Request $request, $product_id) {
        $product = Product::find($product_id);
        $pdt_content = PdtContent::where('product_id', $product_id)->first();
        $pdt_images = PdtImages::where('product_id', $product_id)->get();
        //获取cookie中产品的数量
        $bk_cart = $request->cookie('bk_cart');
        $bk_cart_arr = ($bk_cart!=null ? explode(',', $bk_cart) : array());
        $count = 0;

        $member = $request->session()->get('member', '');
        if($member != '') {
            $cart_items = CartItem::where('member_id', $member->id)->get();
            foreach ($cart_items as $cart_item) {
                if ($product_id == $cart_item->product_id) {
                    $count = $cart_item->count;
                    break;
                }
            }
        }

        foreach ($bk_cart_arr as &$value) { //&为添加引用即可改变数组内的值
            $index = strpos($value, ':');
            if(substr($value, 0, $index) == $product_id) {
                $count = (int)substr($value, $index+1);
                break;
            }
        }
        return view('pdt_content')
                ->with('product', $product)
                ->with('pdt_content', $pdt_content)
                ->with('pdt_images', $pdt_images)
                ->with('count', $count);
    }
}


































