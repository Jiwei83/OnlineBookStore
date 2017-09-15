<?php
/**
 * Created by PhpStorm.
 * User: majiwei
 * Date: 26/08/2017
 * Time: 12:19 PM
 */
namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Mail\Test;
use App\Models\M3Email;
use Illuminate\Http\Request;
use App\Models\M3Result;
use App\Entity\Member;
use App\Entity\TempPhone;
use App\Entity\TempEmail;
use App\Tool\UUID;
use Mail;

class MemberController extends Controller {
    public function register(Request $request)
    {
        $email = $request->input('email', '');
        $phone = $request->input('phone', '');
        $password = $request->input('password', '');
        $confirm = $request->input('confirm', '');
        $phone_code = $request->input('phone_code', '');
        $validate_code = $request->input('validate_code', '');

        $m3_result = new M3Result;

        if($email == '' && $phone == '') {
            $m3_result->status = 1;
            $m3_result->message = 'Phone or email empty';
            return $m3_result->toJson();
        }
        if ($password == '' || strlen($password) < 6) {
            $m3_result->status = 2;
            $m3_result->message = 'Password incorrect';
            return $m3_result->toJson();
        }
        if ($confirm == '' || strlen($confirm) < 6) {
            $m3_result->status = 3;
            $m3_result->message = 'Confirm password incorrect';
            return $m3_result->toJson();
        }
        if ($confirm != $password) {
            $m3_result->status = 4;
            $m3_result->message = 'Confirm password and password not same';
            return $m3_result->toJson();
        }

        // 手机号注册
        if ($phone != '') {
            if ($phone_code == '' || strlen($phone_code) != 6) {
                $m3_result->status = 5;
                $m3_result->message = 'Validate code format incorrect';
                return $m3_result->toJson();
            }

            $tempPhone = TempPhone::where('phone', $phone)->first();
            if ($tempPhone->code == $phone_code) {
                if (time() > strtotime($tempPhone->deadline)) {
                    $m3_result->status = 7;
                    $m3_result->message = 'Phone validate code incorrect';
                    return $m3_result->toJson();
                }

                $member = new Member;
                $member->phone = $phone;
                $member->password = md5('bk' . $password);
                $member->save();

                $m3_result->status = 0;
                $m3_result->message = 'Succeed';
                return $m3_result->toJson();
            } else {
                $m3_result->status = 7;
                $m3_result->message = 'Phone validate code incorrect';
                return $m3_result->toJson();
            }
        }
        //邮箱注册
        else {
            if($validate_code == '' || strlen($validate_code) != 4) {
                $m3_result->status = 6;
                $m3_result->message = 'Email validate code format incorrect';
                return $m3_result->toJson();
            }
            $validate_code_session = $request->session()->get('validate_code', '');
            if($validate_code != $validate_code_session) {
                $m3_result->status = 8;
                $m3_result->message = 'Email validate code incorrect';
                return $m3_result->toJson();
            }
            $member = new Member;
            $member->email = $email;
            $member->password = md5('bk' . $password);
            $member->save();
            $uuid = UUID::create();
            $m3_email = new M3Email;
            $m3_email->to = $email;
            $m3_email->subject = 'Online Book Store Register Validation';
            $m3_email->content = 'Please validate your email within 24 hours through
                        this link: http://127.0.0.1:8000/service/validate_email'
                        . '?member_id=' . $member->id
                        . '&code=' . $uuid;

            Mail::to($email)
                ->send(new Test($m3_email->subject, $m3_email->content));
            $tempEmail = new TempEmail;
            $tempEmail->member_id = $member->id;
            $tempEmail->code = $uuid;
            $tempEmail->deadline = date('Y-m-d H-i-s', time() + 24*60*60);
            $tempEmail->save();

            $m3_result->status = 0;
            $m3_result->message = 'Succeed';
            return $m3_result->toJson();
        }
    }

    public function login(Request $request) {
        $username = $request->input('username', '');
        $password = $request->input('password', '');
        $validate_code = $request->input('validate_code', '');
        $validate_code_session = $request->session()->get('validate_code');
        $m3_result = new M3Result;

        if($validate_code != $validate_code_session) {
            $m3_result->status = 1;
            $m3_result->message = 'Validate code incorrect';
            return $m3_result->toJson();
        }

        if(strpos($username, '@') == true) {
            $member = Member::where('email', $username)->first();
        }
        else {
            $member = Member::where('phone', $username)->first();
        }

        if($member == null) {
            $m3_result->status = 2;
            $m3_result->message = 'Invalid username';
            return $m3_result->toJson();
        }
        else {
            if(md5('bk' . $password) != $member->password) {
                $m3_result->status = 3;
                $m3_result->message = 'Invalid password';
                return $m3_result->toJson();
            }
        }
        $request->session()->put('member', $member);
        $m3_result->status = 0;
        $m3_result->message = 'Succeed';
        return $m3_result->toJson();
    }
}







































