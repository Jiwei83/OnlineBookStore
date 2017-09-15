<?php
/**
 * Created by PhpStorm.
 * User: majiwei
 * Date: 26/08/2017
 * Time: 12:19 PM
 */
namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Entity\Category;
use App\Models\M3Result;

class BookController extends Controller {
    public function getCategoryByParentId($parent_id) {
        $categories = Category::where('parent_id', $parent_id)->get();
        $m3_result = new M3Result;
        $m3_result->status = 0;
        $m3_result->message = 'Return Succeeds';
        $m3_result->categories = $categories;
        return $m3_result->toJson();
    }
}