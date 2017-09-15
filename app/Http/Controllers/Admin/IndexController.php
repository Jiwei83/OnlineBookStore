<?php

/**
 * Created by PhpStorm.
 * User: majiwei
 * Date: 10/09/2017
 * Time: 12:12 PM
 */
namespace App\Http\Controllers\Admin;

use App\Entity\Category;
use App\Http\Controllers\Controller;
use App\Models\M3Result;
use Illuminate\Http\Request;

class IndexController extends Controller {
    public function login() {
        return redirect('admin/index');
    }

    public function toIndex() {
        return view('admin.index');
    }

    public function toCategory() {
        $categories = Category::all();
        foreach($categories as $category) {
            if($category->parent_id != null && $category->parent_id != '') {
                $category->parent = Category::where('id', $category->parent_id)->first();
            }
        }
        return view('admin.category', ['categories' => $categories]);
    }

    public function toLogin() {
        return view('admin.login');
    }

    public function toCategoryAdd() {
        $categories = Category::whereNull('parent_id')->get();
        return view('admin.category_add', ['categories' => $categories]);
    }

    public function toCategoryEdit(Request $request) {
        $id = $request->input('id', '');
        $category = Category::where('id', $id)->first();
        $parent = Category::where('id', $category->parent_id)->first();
        $category->parent = $parent;
        $categories = Category::whereNull('parent_id')->get();
        return view('admin.category_edit', ['category' => $category, 'categories' => $categories]);
    }

    /*********************************SERVICE******************************************/
    public function categoryAdd(Request $request) {
        $category_name = $request->input('name', '');
        $category_no = $request->input('category_no', '');
        $parent_id = $request->input('parent_id', '');
        $m3_result = new M3Result;

        if($category_name != '' && $category_no != '' && $parent_id != '') {
            $category = new Category;
            $category->name = $category_name;
            $category->category_no = $category_no;
            $category->parent_id = $parent_id;
            $category->save();

            $m3_result->status = 0;
            $m3_result->message = '添加成功';
            return $m3_result->toJson();
        }
        else {
            $m3_result->status = 1;
            $m3_result->message = '添加失败';
            return $m3_result->toJson();
        }


    }

    public function categoryDelete(Request $request) {
        $id = $request->input('id', '');
        $m3_result = new M3Result;

        if($id != '') {
            Category::find($id)->delete();

            $m3_result->status = 0;
            $m3_result->message = '删除成功';
            return $m3_result->toJson();
        }
        else {
            $m3_result->status = 1;
            $m3_result->message = '删除失败';
            return $m3_result->toJson();
        }
    }

    public function categoryEdit(Request $request) {
        $id = $request->input('id', '');
        $category_name = $request->input('name', '');
        $category_no = $request->input('category_no', '');
        $parent_id = $request->input('parent_id', '');
        $m3_result = new M3Result;
        if($id != '' && $category_name != '' && $category_no != '' && $parent_id != '') {
            $category = Category::where('id', $id)->first();
            $category->name = $category_name;
            $category->category_no = $category_no;
            $category->parent_id = $parent_id;
            $category->save();

            $m3_result->status = 0;
            $m3_result->message = '修改成功';
            return $m3_result->toJson();
        }
        else {
            $m3_result->status = 1;
            $m3_result->message = '修改失败';
            return $m3_result->toJson();
        }


    }
}