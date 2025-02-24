<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class shopcart extends Model
{
    use HasFactory;
  protected $fillable = [
    'isOnCredit', // Buraya ekledik
    'isPaid',
    'table_id',
    'all_total',
    'left_total'
  ];
}
