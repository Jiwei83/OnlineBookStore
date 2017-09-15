<?php
/**
 * Created by PhpStorm.
 * User: majiwei
 * Date: 31/08/2017
 * Time: 11:00 AM
 */

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class TempPhone extends Model{
    protected $table = 'temp_phone';
    protected $id = 'id';
    public $timestamps = false;
}