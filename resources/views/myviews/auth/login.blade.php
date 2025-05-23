@extends('layouts/blankLayout')

@section('title', 'Login Basic - Pages')

@section('page-style')
  <!-- Page -->
  <link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-auth.css')}}">
@endsection

@section('content')
  <div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
      <div class="authentication-inner">
        <!-- Register -->
        <div class="card">
          <div class="card-body">
            <!-- Logo -->
            <div class="app-brand justify-content-center">
              <a href="{{url('/')}}" class="app-brand-link gap-2">
                <span class="app-brand-logo demo"></span>
                <span class="app-brand-text demo text-body fw-bold">bidemlik</span>
              </a>
            </div>
            <!-- /Logo -->
            <h4 class="mb-2">Bidemlik adisyon sistemine hoşgeldin! 👋</h4>
            <form id="formAuthentication" class="mb-3" action="{{ route('login') }}" method="POST">
              @csrf
              <div class="mb-3">
                <label for="email" class="form-label">Email Ve Şifre Giriniz</label>
                <input type="text" class="form-control" id="email" name="email" placeholder="Email" autofocus>
              </div>
              <div class="mb-3 form-password-toggle">
                <div class="input-group input-group-merge">
                  <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                  <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                </div>
              </div>
              <div class="mb-3">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="remember-me" name="remember">
                  <label class="form-check-label" for="remember-me">
                    Beni Hatırla
                  </label>
                </div>
              </div>
              <div class="mb-3">
                <button class="btn btn-primary d-grid w-100" type="submit">Giriş Yap</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- /Register -->
    </div>
  </div>
  </div>
@endsection
