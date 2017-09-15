<?php
/**
 * Created by PhpStorm.
 * User: majiwei
 * Date: 26/08/2017
 * Time: 12:34 PM
 */
namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model {
    protected $table = 'cart_item';
    protected $primaryKey = 'id';

}