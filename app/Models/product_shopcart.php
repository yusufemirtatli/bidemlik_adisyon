<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product_shopcart extends Model
{
  use HasFactory;

  protected $table = 'product_shopcarts';

  protected $fillable = ['product_id', 'shopcart_id', 'quantity', 'price']; // Gerekli sütunları buraya ekleyin


  public function product()
  {
    return $this->belongsTo(product::class);
  }
}
