@php
  $container = 'container-xxl';
  $containerNav = 'container-xxl';
@endphp

@extends('layouts.contentNavbarLayout')

@section('title', 'Container - Layouts')

@section('content')
  <!-- Layout wrapper -->
  <div class="layout-wrapper layout-content-navbar">
    <!-- Layout container -->
    <div class="layout-container">
      <!-- Menu -->
      <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
        @include('layouts.sections.menu.verticalMenu')
      </aside>
      <!--/ Menu -->
      <!--/ Content  -->
      <div class="container">
        <h1>Site Ayarları</h1>
        @if(session('success'))
          <div class="alert alert-success">
            {{ session('success') }}
          </div>
        @endif
        <div class="d-flex flex-column w-25">
          <label for="db" class="form-label">Son 2 ay hariç bütün database'i temizle.</label>
          <form id="dbForm" action="{{ route('database.clean') }}" method="POST">
            @csrf
            <button id="db" type="button" class="btn btn-danger w-75" aria-describedby="dbs">Database Temizle</button>
          </form>
          <div id="dbs" class="form-text">Lütfen database sorguları yavaşladığında yapın. Son 2 ay hariç bütün verilerinizi kaybedeceksiniz</div>
        </div>
      </div>
    <!--/ Layout container -->
  </div>
  <!--/ Layout wrapper -->
    <script>
      document.getElementById("db").addEventListener("click", function () {
        Swal.fire({
          title: "Emin misiniz?",
          text: "Son 2 ay hariç tüm verileriniz silinecek! Bu işlem geri alınamaz.",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#d33",
          cancelButtonColor: "#3085d6",
          confirmButtonText: "Evet, sil!",
          cancelButtonText: "İptal"
        }).then((result) => {
          if (result.isConfirmed) {
            document.getElementById("dbForm").submit();
          }
        });
      });
    </script>

    <!-- SweetAlert2 Kütüphanesi -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
