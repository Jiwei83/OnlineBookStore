<?php
/**
 * Created by PhpStorm.
 * User: majiwei
 * Date: 26/08/2017
 * Time: 12:19 PM
 */
namespace App\Http\Controllers\View;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MemberController extends Controller {
    public function toLogin(Request $request) {
        $return_url = $request->input('return_url', '');
        return view('login')->with('return_url', urldecode($return_url));
    }

    public function toRegister() {
        return view('register');
    }
}