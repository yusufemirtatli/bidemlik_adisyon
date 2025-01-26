@php
  $container = 'container-xxl';
  $containerNav = 'container-xxl';
@endphp

@extends('layouts.contentNavbarLayout')

@section('title', 'Container - Layouts')

@section('content')

  <!-- Layout wrapper -->

  <style>
    .table-responsive {
      overflow-x: auto;
    }

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
            <h1 class="text-center mb-4">Stok Yönetimi</h1>

            <!-- Ürün Ekleme Formu -->
            <div class="card mb-4">
              <div class="card-header">
                <h3>Ürün Ekle</h3>
              </div>
              <div class="card-body">
                <form id="urunFormu">
                  <div class="row mb-3">
                    <div class="col">
                      <input type="text" class="form-control" id="urunAdi" placeholder="Ürün Adı" required>
                    </div>
                    <div class="col">
                      <input type="date" class="form-control" id="sonKullanmaTarihi" placeholder="Son Kullanma Tarihi" required>
                    </div>
                  </div>
                  <div class="row mb-3">
                    <div class="col">
                      <input type="number" class="form-control" id="adet" placeholder="Adet" required>
                    </div>
                    <div class="col">
                      <input type="number" class="form-control" id="fiyat" placeholder="Fiyat (TL)" required>
                    </div>
                  </div>
                  <div class="mb-3">
                    <select class="form-select" id="kategori" required>
                      <option selected disabled>Kategori Seç</option>
                      <option value="gida">Gıda</option>
                      <option value="icecek">İçecek</option>
                      <option value="temizlik">Temizlik</option>
                    </select>
                  </div>
                  <button type="submit" class="btn btn-primary">Ekle</button>
                </form>
              </div>
            </div>

            <!-- Gıda Kategorisi -->
            <div class="card mb-4">
              <div class="card-header">
                <h3>Gıda</h3>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-striped" id="gidaTablo">
                    <thead>
                    <tr>
                      <th>Ürün Adı</th>
                      <th>Son Kullanma Tarihi</th>
                      <th>Adet</th>
                      <th>Fiyat</th>
                      <th>İşlem</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!-- Ürünler burada listelenecek -->
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
                  <table class="table table-striped" id="icecekTablo">
                    <thead>
                    <tr>
                      <th>Ürün Adı</th>
                      <th>Son Kullanma Tarihi</th>
                      <th>Adet</th>
                      <th>Fiyat</th>
                      <th>İşlem</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!-- Ürünler burada listelenecek -->
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
                  <table class="table table-striped" id="temizlikTablo">
                    <thead>
                    <tr>
                      <th>Ürün Adı</th>
                      <th>Son Kullanma Tarihi</th>
                      <th>Adet</th>
                      <th>Fiyat</th>
                      <th>İşlem</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!-- Ürünler burada listelenecek -->
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <script>
            // Formu işleme ve ürünü tabloya ekleme
            document.getElementById('urunFormu').addEventListener('submit', function (e) {
              e.preventDefault();

              // Form verilerini al
              const urunAdi = document.getElementById('urunAdi').value;
              const sonKullanmaTarihi = document.getElementById('sonKullanmaTarihi').value;
              const adet = document.getElementById('adet').value;
              const fiyat = document.getElementById('fiyat').value;
              const kategori = document.getElementById('kategori').value;

              // Uygun tabloyu seç
              let tabloId;
              if (kategori === 'gida') {
                tabloId = 'gidaTablo';
              } else if (kategori === 'icecek') {
                tabloId = 'icecekTablo';
              } else if (kategori === 'temizlik') {
                tabloId = 'temizlikTablo';
              }

              // Tabloya yeni satır ekle
              const tablo = document.getElementById(tabloId).querySelector('tbody');
              const yeniSatir = tablo.insertRow();
              yeniSatir.innerHTML = `
                <td>${urunAdi}</td>
                <td>${sonKullanmaTarihi}</td>
                <td>${adet}</td>
                <td>${fiyat} TL</td>
                <td><button class="btn btn-danger btn-sm" onclick="urunSil(this)">Sil</button></td>
            `;

              // Formu sıfırla
              document.getElementById('urunFormu').reset();
            });

            // Ürünü tablodan silme
            function urunSil(button) {
              const satir = button.parentElement.parentElement;
              satir.remove();
            }
          </script>

          <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        </div>
      </div>
      <!--/END Content  -->
    </div>
    <!--/ Layout container -->
  </div>
  <!--/ Layout wrapper -->
@endsection

