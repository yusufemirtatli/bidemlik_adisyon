<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product_shopcart extends Model
{
  use HasFactory;

  protected $table = 'product_shopcarts';

  public function product()
  {
    return $this->belongsTo(product::class);
  }
}
