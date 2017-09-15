<?php
/**
 * Created by PhpStorm.
 * User: majiwei
 * Date: 26/08/2017
 * Time: 12:34 PM
 */
namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class Member extends Model {
    protected $table = 'member';
    protected $primaryKey = 'id';

}