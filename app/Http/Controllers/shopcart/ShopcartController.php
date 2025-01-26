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
    $table_id = $request->input('table');
    $shopcart_id = shopcart::where('table_id', $table_id)
      ->where('isPaid', false)
      ->pluck('id')
      ->first();

    $arrays = $request->input('array'); // Array: [ [product_id, quantity], ... ]

    if (!$shopcart_id) {
      return response()->json(['error' => 'Shopcart not found or is already paid.'], 404);
    }

    foreach ($arrays as $array) {
      $product_id = $array[0];
      $quantity_to_add = $array[1];

      // Belirtilen ürün mevcut mu?
      $productShopcart = product_shopcart::where('shopcart_id', $shopcart_id)
        ->where('product_id', $product_id)
        ->where('isPaid', false)
        ->first();

      if ($productShopcart) {
        // Ürün mevcutsa quantity artır
        $productShopcart->quantity += $quantity_to_add;
        $productShopcart->save();
      } else {
        // Ürün yoksa yeni kayıt oluştur
        product_shopcart::create([
          'product_id' => $product_id,
          'shopcart_id' => $shopcart_id,
          'quantity' => $quantity_to_add,
          'isPaid' => false,
        ]);
      }
    }

    // Güncellenen verileri döndür
    $updatedShopcart = product_shopcart::where('shopcart_id', $shopcart_id)->get();
    return response()->json($updatedShopcart);
  }



  public function updateDatabase(Request $request)
  {
    // JSON verisini al
    $products = $request->input('products');  // 'products' anahtarını kullanarak arrayi alıyoruz
    $errors = []; // Hata dizisini oluşturuyoruz

    // Array olarak gönderilen shopcart_productsları teker teker işliyoruz
    foreach ($products as $product) {
      $productShopcartId = $product['product_shopcart_id']; // Her bir ürün için product_shopcart_id alıyoruz
      $quantity = $product['quantity'];  // Her bir ürün için quantity alıyoruz

      // Burada gelen arrayin içinden shopcart_product'ın id'sini alıp
      // database'de o id ile sorgulama yapıp quantity değerini güncelliyoruz
      $item = product_shopcart::find($productShopcartId);

      // Eğer ürün bulunamazsa, hata ekle
      if (!$item) {
        $errors[] = "Product with shopcart_id {$productShopcartId} not found.";
        continue; // Eğer ürün bulunamazsa işlemi atla
      }

      // Ürün bulunduysa quantity değerini güncelle
      $item->quantity = $quantity;

      try {
        // Güncelleme işlemini yap
        $item->save();
      } catch (\Exception $e) {
        // Hata alırsak, hatayı logla
        $errors[] = "Failed to update product with shopcart_id {$productShopcartId}: " . $e->getMessage();
      }
    }

    // Eğer hata varsa, hata mesajları ile birlikte dönebiliriz
    if (!empty($errors)) {
      return response()->json(['success' => false, 'errors' => $errors]);
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
