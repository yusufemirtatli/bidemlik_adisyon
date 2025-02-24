<?php

namespace App\Http\Controllers\veresiye;

use App\Http\Controllers\Controller;
use App\Models\onCredit;
use App\Models\shopcart;
use Carbon\Carbon;

class veresiye extends Controller
{
    public function index(){
      $veresiyeler = onCredit::all();
      return view('myviews.veresiye.veresiye',[
        'veresiyeler'=>$veresiyeler,
      ]);
    }

    public function tahsil($id){
      $sc = shopcart::find($id);
      $sc->isOnCredit = 0;
      $sc->created_at = Carbon::now();
      $sc->save();
      onCredit::where('shopcart_id',$id)->delete();
      return redirect(route('veresiye-index'))->with('success','Veresiye başarıyla tahsil edildi');
    }
}
