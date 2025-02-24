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

  public function create(Request $request) {
    // Verileri doğrulama
    $validated = $request->validate([
      'category' => 'required|integer', // Kategori ID kontrolü
      'name' => 'required|string|max:255', // Gider ismi kontrolü
      'amount' => 'required|numeric|min:0', // Tutar kontrolü
      'date' => 'required|date_format:Y-m', // Yıl ve Ay formatı kontrolü
    ]);


    // Doğru tarih formatını oluştur
    $created_at = \Carbon\Carbon::createFromFormat('Y-m', $validated['date'])
      ->startOfMonth()
      ->setTime(12, 0, 0); // Saat 12:00 olarak ayarla

    $gider = new Gider();
    $gider->cat_id = $validated['category'];
    $gider->name = $validated['name'];
    $gider->amount = $validated['amount'];
    $gider->forceFill(['created_at' => $created_at])->save();

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
    $sub_data = gider::where('cat_id',$id)->get();
    foreach ($sub_data as $rs){
        $rs->delete();
    }
    $data->delete();

    return redirect(route('gider-category'));
  }

  public function subDestroy($id){
      $data = gider::find($id);
      $data->delete();

      return redirect(route('gider-index'));
  }
}
