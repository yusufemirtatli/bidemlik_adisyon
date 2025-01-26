<?php

namespace App\Http\Controllers\table;

use App\Http\Controllers\Controller;
use App\Models\category;
use App\Models\product;
use App\Models\product_shopcart;
use App\Models\shopcart;
use App\Models\tables;
use Illuminate\Http\Request;
use Laravel\Prompts\Table;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $tables = tables::all();
      $shopcarts = shopcart::all();
        return view('myviews.tables.tables-index',[
          'tables'=>$tables,
          'shopcarts' => $shopcarts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
      $data = new tables();
      $data->title = $request->title;
      $data->save();

      return redirect()->back();
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

  public function detail($id)
  {
    $shopcart_id_first = shopcart::where('table_id', $id)
      ->where('isPaid', false)
      ->pluck('id')
      ->first();

    session(['TableId' => $id]);

    $hasUnpaidItems = shopcart::where('table_id', $id)
      ->where('isPaid', false)
      ->exists();
    if ($hasUnpaidItems) {
      // `isPaid` sütunu false olan kayıtlar var
      $shopcart_id = shopcart::where('table_id', $id)
        ->where('isPaid', false)
        ->pluck('id');
    }else {
      $sc = new shopcart();
      $sc->table_id = $id;
      $sc->save();
      $shopcart_id = shopcart::where('table_id', $id)
        ->where('isPaid', false)
        ->pluck('id');
    }
    //*****************************

    $products = product_shopcart::where('shopcart_id', $shopcart_id)
      ->where('isPaid', false)
      ->where('quantity', '>', 0)
      ->get();

    $categories = category::all();
    $table = tables::find($id);
    $shopcart = shopcart::find($shopcart_id);
    return view('myviews.tables.table-detail',[
      'table'=>$table,
      'table_id' => $id,
      'categories' => $categories,
      'products' => $products,
      'shopcartId' => $shopcart_id_first,
      'shopcart' => $shopcart,
    ]);
  }

  function updateTableTotals(Request $request)
  {
    $shopcart_id = $request->input('shopcartId');
    $shopcart = shopcart::find($shopcart_id);

    if (!$shopcart) {
      return response()->json(['success' => false, 'message' => 'Shopcart not found'], 404);
    }

    // İlgili tüm product_shopcart ve product verilerini tek seferde yükleyelim
    $shopcart_products = product_shopcart::where('shopcart_id', $shopcart_id)->get();
    $product_ids = $shopcart_products->pluck('product_id')->toArray();
    $products = product::whereIn('id', $product_ids)->get()->keyBy('id');

    // Toplamları sıfırlayalım
    $all_total = 0;
    $left_total = 0;

    foreach ($shopcart_products as $sc_product) {
      $product = $products->get($sc_product->product_id);

      if ($product) {  // Eğer ürün bulunursa işlemleri yap
        $product_total = $product->price * $sc_product->quantity;

        // Tüm ürünlerin toplamı
        $all_total += $product_total;

        // Sadece isPaid değeri 0 olan ürünlerin toplamı
        if ($sc_product->isPaid == 0) {
          $left_total += $product_total;
        }
      }
    }

    // Hesaplanan toplamları güncelle
    $shopcart->all_total = $all_total;
    $shopcart->left_total = $left_total;
    if ($shopcart->all_total > $shopcart->left_total && $shopcart->left_total == 0){
      $shopcart->isPaid = 1;
      $shopcart->save();
      return response()->json([
        'success' => true,
        'redirect_url' => '/masa' // Redirect URL'yi JSON olarak gönderiyoruz
      ]);
    }
    $shopcart->save();

    return response()->json(['success' => true,
      'message' => 'Totals updated successfully',
      'all_total' => $all_total,
      'left_total' => $left_total]);
  }
}
