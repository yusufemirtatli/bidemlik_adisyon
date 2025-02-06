<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\category;
use App\Models\gider;
use App\Models\product;
use App\Models\product_shopcart;
use App\Models\shopcart;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $monthlyAdisyonByMonth = Shopcart::whereBetween('created_at', [
      Carbon::now()->subMonths(3)->startOfMonth(), // 4 ay önceki ayın başı
      Carbon::now()->endOfMonth() // Bu ayın sonu
    ])
      ->where('ispaid', 1)
      ->get()
      ->groupBy(function ($item) {
        return Carbon::parse($item->created_at)->format('Y-m'); // Tarihi "YYYY-MM" formatında gruplar
      })
      ->sortKeysDesc(); // Ayları en yeni olandan eskiye sıralar

    $monthlyAdisyonByDay = Shopcart::whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
      ->where('ispaid', 1)
      ->get()
      ->groupBy(function ($item) {
        return Carbon::parse($item->created_at)->format('Y-m-d'); // Tarihi "YYYY-MM-DD" formatında gruplar
      })
      ->sortKeysDesc(); // Tarihe göre en yeni olanı önce sıralar

    $monthlyAdisyon = shopcart::whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
      ->where('ispaid', 1)
      ->get();

    $dailyAdisyon = shopcart::whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])
      ->where('ispaid', 1)
      ->get();

    $monthlySales = product_shopcart::whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->get();
    $dailySales = product_shopcart::whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])->get();
    $categories = category::all();
    return view('myviews.dashboard.dashboard',[
      'dailySales' => $dailySales,
      'monthlySales' => $monthlySales,
      'categories' => $categories,
      'monthlyAdisyon' => $monthlyAdisyon,
      'dailyAdisyon' => $dailyAdisyon,
      'monthlyAdisyonByDay' => $monthlyAdisyonByDay,
      'monthlyAdisyonByMonth' => $monthlyAdisyonByMonth,

    ]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    //
  }
}
