<?php

namespace App\Http\Controllers\shopcart;

use App\Http\Controllers\Controller;
use App\Models\product;
use App\Models\product_shopcart;
use App\Models\shopcart;
use App\Models\tables;
use Illuminate\Http\Request;

class ShopcartController extends Controller
{
 public function update(Request $request)
  {
    //* Table id'yi alıyor ve bu masaya atanan ödenmemiş shopcartları listeliyor
    $table_id = $request->input('table');
    $shopcart_id = shopcart::where('table_id', $table_id)
      ->where('isPaid', false)
      ->pluck('id')->first(); // İlk shopcart ID'sini alıyoruz

    $arrays = $request->input('array');
    // Bu shopcart'a ait tüm ürünleri alıyoruz
    $shopcarts = product_shopcart::where('shopcart_id', $shopcart_id)->get();

    foreach ($arrays as $array) {
      // İlk olarak ürünün mevcut olup olmadığını kontrol ediyoruz
      $sc = $shopcarts->where('product_id', $array[0])->where('isPaid',false)->first();
      if ($sc) {
        // Eğer mevcutsa, quantity değerini güncelliyoruz
        $sc->quantity += $array[1];
        $sc->save();
        echo "<script>console.log('$sc');</script>";
      } else{
        // Eğer mevcut değilse, yeni bir ürün oluşturuyoruz
        $data = new product_shopcart();
        $data->product_id = $array[0];
        $data->shopcart_id = $shopcart_id;
        $data->quantity = $array[1];
        $data->isPaid = false; // Varsayılan olarak isPaid false olarak ayarlanıyor
        $data->save();
      }
    }
  }

  public function updateDatabase(Request $request)
  {
    // JSON verisini al
    $products = $request->input('products');  // 'products' anahtarını kullanarak arrayi alıyoruz

    // Array olarak göndelirlen shopcart_productsları teker teker verilerini alma
    foreach ($products as $product) {
      $productShopcartId = $product['product_shopcart_id']; // Her bir ürün için product_shopcart_id alıyoruz
      $quantity = $product['quantity'];  // Her bir ürün için quantity alıyoruz

      // Burada gelen arrayin içinden shopcart_product'ın id sini alıp
      // database'de o id ile sorgulama yapıp quantity değerini güncelliyoruz
      $item = product_shopcart::find($productShopcartId);
      $item->quantity = $quantity;
      $item->save();

    }

    return response()->json(['success' => true, 'data' => $products]);
  }

  public function updateDatabasePaid(Request $request)
  {
    $products = $request->input('products');  // 'products' anahtarını kullanarak arrayi alıyoruz
    foreach ($products as $product) {
      $productShopcartId = $product['product_shopcart_id']; // Her bir ürün için product_shopcart_id alıyoruz
      $quantity = $product['quantity'];  // Her bir ürün için quantity alıyoruz
      $product_id = $product['product_id'];  // Her bir ürün için quantity alıyoruz
      $item = product_shopcart::find($productShopcartId);
      if ($item) {
        $item->quantity = $item->quantity - $quantity ;
        $item->save();
        if ($item->quantity == 0){
          $item->delete();
        }
        $paidItem = product_shopcart::where('shopcart_id', $item->shopcart_id)
          ->where('product_id', $product_id)
          ->where('isPaid', true)
          ->first();

        if ($paidItem) {
          $paidItem->quantity += $quantity;
          $paidItem->save();
        } else {
          $data = new product_shopcart();
          $data->product_id = $product_id;
          $data->quantity = $quantity;
          $data->shopcart_id = $item->shopcart_id;
          $data->isPaid = true;  // isPaid alanını da ekleyin
          $data->save();
        }
      }
    }
    return response()->json(['success' => true, 'data' => $products]);
  }

  public function updateDatabaseRefund(Request $request){
    $products = $request->input('products');  // 'products' anahtarını kullanarak arrayi alıyoruz

    foreach ($products as $product) {
      $productShopcartId = $product['product_shopcart_id']; // Her bir ürün için product_shopcart_id alıyoruz
      $quantity = $product['quantity'];  // Her bir ürün için quantity alıyoruz
      $item = product_shopcart::find($productShopcartId);
      if ($item){
        $item->quantity = $item->quantity - $quantity;
        $item->save();
        if ($item->quantity == 0){
          $item->delete();
        }
      }
    }
    return response()->json(['success' => true, 'data' => $products]);
  }



  /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
