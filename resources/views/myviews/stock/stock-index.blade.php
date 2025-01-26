

@php
  $container = 'container-xxl';
  $containerNav = 'container-xxl';
@endphp

@extends('layouts.contentNavbarLayout')

@section('title', 'Container - Layouts')

@section('content')

  <!-- Layout wrapper -->

  <style>
    /* Tabloların taşmasını önlemek için */
    .table-responsive {
      overflow-x: auto;
    }

    /* Mobil cihazlar için başlık ve tablo arası boşluklar */
    @media (max-width: 576px) {
      .card-header {
        text-align: center;
      }

      .table-responsive {
        margin-top: 10px;
      }
    }
  </style>

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
        <div class="row">
          <div class="container mt-5">
            <h1 class="text-center mb-4">Stok Görüntüleme</h1>

            <!-- Gıda Kategorisi -->
            <div class="card mb-4">
              <div class="card-header">
                <h3>Gıda</h3>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-striped">
                    <thead>
                    <tr>
                      <th>Ürün Adı</th>
                      <th>Son Kullanma Tarihi</th>
                      <th>Adet</th>
                      <th>Fiyat</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                      <td>Elma</td>
                      <td>01.09.2024</td>
                      <td>50</td>
                      <td>5.00 TL</td>
                    </tr>
                    <tr>
                      <td>Ekmek</td>
                      <td>15.09.2023</td>
                      <td>100</td>
                      <td>3.00 TL</td>
                    </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <!-- İçecek Kategorisi -->
            <div class="card mb-4">
              <div class="card-header">
                <h3>İçecek</h3>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-striped">
                    <thead>
                    <tr>
                      <th>Ürün Adı</th>
                      <th>Son Kullanma Tarihi</th>
                      <th>Adet</th>
                      <th>Fiyat</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                      <td>Su</td>
                      <td>01.12.2025</td>
                      <td>200</td>
                      <td>1.00 TL</td>
                    </tr>
                    <tr>
                      <td>Meyve Suyu</td>
                      <td>01.01.2024</td>
                      <td>150</td>
                      <td>7.00 TL</td>
                    </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <!-- Temizlik Kategorisi -->
            <div class="card mb-4">
              <div class="card-header">
                <h3>Temizlik</h3>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-striped">
                    <thead>
                    <tr>
                      <th>Ürün Adı</th>
                      <th>Son Kullanma Tarihi</th>
                      <th>Adet</th>
                      <th>Fiyat</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                      <td>Çamaşır Suyu</td>
                      <td>01.11.2024</td>
                      <td>80</td>
                      <td>12.00 TL</td>
                    </tr>
                    <tr>
                      <td>Deterjan</td>
                      <td>01.10.2024</td>
                      <td>60</td>
                      <td>25.00 TL</td>
                    </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        </div>
      </div>
      <!--/END Content  -->
    </div>
    <!--/ Layout container -->
  </div>
  <!--/ Layout wrapper -->
@endsection

