<?php
/**
 * Created by PhpStorm.
 * User: majiwei
 * Date: 26/08/2017
 * Time: 12:19 PM
 */
namespace App\Http\Controllers\View;

use App\Http\Controllers\Controller;
use App\Entity\Product;
use Illuminate\Http\Request;

class OrderController extends Controller {
    public function toOrderPay(Request $request) {
        return view('order_pay');
    }
}


































