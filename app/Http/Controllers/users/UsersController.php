<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use App\Models\shopcart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("myviews.users.users-index");
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
    public function loginPage(){
        return view('myviews.auth.login');
    }
    public function login(Request $request)
    {

      // Bugünden önceki günlere ait, isPaid = 0 ve all_total = 0 olan shopcart kayıtlarını sil
      $emptyShopcarts = shopcart::where('all_total', 0)
        ->where('isPaid', 0)
        ->whereDate('created_at', '<', now()->toDateString()) // Bugünden önceki günleri al
        ->delete();

        // Validasyon
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'], // Email doğru formatta olmalı
            'password' => ['required', 'string']
        ]);

        // Giriş yapma denemesi
        if (Auth::attempt($credentials, $request->has('remember'))) {
            // Başarılı girişte yönlendirme
            return redirect()->intended('/dashboard');
        }

        // Başarısız girişte geri yönlendir
        return back()->withErrors([
            'email' => 'Girdiğiniz bilgiler yanlış.',
        ])->withInput();
    }
}
