<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use App\Models\category;
use App\Models\product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $listproducts = product::where('isListed',true)->get();
    $unlistproducts = product::where('isListed',false)->get();
    $categories = category::all();
    return view('myviews.menu.food-menu',[
      'listproducts'=>$listproducts,
      'unlistproducts'=>$unlistproducts,
      'categories' => $categories,
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
    $request->validate([
      'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Görsel için doğrulama
    ]);
    $imagePath = null;

    if ($request->hasFile('image')) {
      $imagePath = $request->file('image')->store('products', 'public'); // Görseli "storage/app/public/products" içine kaydeder
    }

    $data = new product();
    $data->title = $request->title;
    $data->cost = $request->cost;
    $data->price = $request->price;
    $data->desc = $request->desc;
    $data->image = $imagePath;
    $data->isListed = 1;
    $data->category_id = $request->category_id;
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
  public function category()
  {
    $category = category::all();
    return view('myviews.menu.menu-category',[
      'category'  =>  $category,
    ]);
  }

  public function addCategory(Request $request){
    $data = new category();
    $data->title = $request->title;
    $data->save();

    return redirect(route('menu-category'));
  }
  public function deleteCategory($id){
    $data = category::find($id);
    $data->delete();

    return redirect(route('menu-category'));
  }

  public function foodDetail($id){
    $food = product::find($id);
    $category = category::all();

    return view('myviews.menu.food-detail',[
      'food' => $food,
      'categories' => $category,
    ]);
  }

  public function foodDetailUpdate(Request $request, $id)
  {
    $food = product::find($id);
    $category = category::all();

    $request->validate([
      'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Görsel için doğrulama
    ]);

    // Görsel yüklendi mi kontrol et
    if ($request->hasFile('image')) {
      // Yeni görseli kaydet
      $imagePath = $request->file('image')->store('products', 'public'); // Görseli "storage/app/public/products" içine kaydeder

      // Eski görseli sil (isteğe bağlı)
      if ($food->image && Storage::disk('public')->exists($food->image)) {
        Storage::disk('public')->delete($food->image);
      }

      $food->image = $imagePath; // Yeni görsel yolunu kaydet
    }

    // Eğer görsel yüklenmemişse, mevcut görseli koru
    // (Bu durumda `$food->image` zaten değişmeyecek)

    $food->title = $request->title;
    $food->cost = $request->cost;
    $food->desc = $request->desc;
    $food->category_id = $request->category;
    $food->price = $request->price;
    // Checkbox kontrolü
    $food->isListed = $request->has('isListed') ? true : false;
    $food->save();

    return redirect(route('menu-product'));
  }

}
