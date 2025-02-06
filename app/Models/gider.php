<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class gider extends Model
{
    use HasFactory;

  protected $fillable = [
    'cat_id',
    'name',
    'amount',
  ];
}
