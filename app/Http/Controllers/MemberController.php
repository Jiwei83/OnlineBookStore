<?php
/**
 * Created by PhpStorm.
 * User: majiwei
 * Date: 26/08/2017
 * Time: 12:19 PM
 */
namespace App\Http\Controllers;

use App\Member;

class MemberController extends Controller {
    public function info($id) {
        //return 'member-info-'.$id;
        //return view('member/info', ['name' => 'test', 'age' => '28']);
        return Member::getMember();
    }
}