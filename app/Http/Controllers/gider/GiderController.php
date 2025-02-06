<?php

namespace App\Http\Controllers\gider;

use App\Http\Controllers\Controller;
use App\Models\gider;
use App\Models\gider_cat;
use Illuminate\Http\Request;

class GiderController extends Controller
{
  public function index(){
    $gider_category = gider_cat::all();
    return view('myviews.gider.gider',[
      'giderCategory' => $gider_category,
    ]);
  }

  public function create(Request $request){
    // Verileri doğrulama
    $validated = $request->validate([
      'category' => 'required|integer|exists:categories,id', // Kategori ID'si kontrolü
      'name' => 'required|string|max:255', // Gider ismi kontrolü
      'amount' => 'required|numeric|min:0', // Tutar kontrolü
    ]);

    // Verileri oluşturma
    Gider::create([
      'cat_id' => $validated['category'],
      'name' => $validated['name'],
      'amount' => $validated['amount'],
    ]);

    // Başarıyla kaydedildikten sonra yönlendirme
    return redirect(route('gider-index'));
  }

  public function category(){
    $gider_category = gider_cat::all();
    return view('myviews.gider.gider-category',[
      'giderCategory' => $gider_category,
    ]);
  }

  public function storeCategory(Request $request){
    $data = new gider_cat();
    $data->name = $request->name;
    $data->save();

    return redirect(route('gider-category'));
  }

  public function destroy($id){
    $data = gider_cat::find($id);
    $data->delete();

    return redirect(route('gider-category'));
  }
}
