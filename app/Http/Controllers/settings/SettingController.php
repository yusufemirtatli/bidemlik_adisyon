<?php

namespace App\Http\Controllers\settings;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('myviews.settings.settings');
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

    public function clearDb(){
      // 2 ay öncesinin tarihini al
      $twoMonthsAgo = Carbon::now()->subMonths(2);

      // Eski verileri temizle
      DB::table('shopcarts')->where('created_at', '<', $twoMonthsAgo)->delete();
      DB::table('product_shopcarts')->where('created_at', '<', $twoMonthsAgo)->delete();
      DB::table('giders')->where('created_at', '<', $twoMonthsAgo)->delete();

      return redirect()->back()->with('success', 'Eski veriler başarıyla temizlendi!');
    }
}
