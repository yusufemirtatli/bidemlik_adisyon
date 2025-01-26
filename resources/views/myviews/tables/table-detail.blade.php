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
      @include('myviews.tables.components.add-prod-to-shopcart')

      @include('myviews.tables.components.split-shopcart')
      <!--/ Content  -->
      <div class="container">
        <div class="row">
          <div class="card" style="margin-bottom: 15px">
            <div class="d-flex flex-row justify-content-between">
              <h5 class="card-header d-flex">Adisyon Toplam</h5>
              <div class="d-flex" style="place-items: center">
                <button type="button" class="d-flex h-50 btn btn-info" data-bs-toggle="modal" data-bs-target="#addprod">
                  Ürün Ekle
                </button>
              </div>
            </div>
            <div class="table-responsive text-nowrap">
              <table class="table table-striped">
                <thead>
                <tr>
                  <th>Ürün</th>
                  <th style="text-align: left;padding-left: 5vh">Miktar</th>
                  <th>Tane Fiyatı</th>
                  <th>Toplam Fiyat</th>
                  <th>Sil</th>
                </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                @foreach($products as $product)
                  <tr class="product-row-tr"
                      data-product-shopcart-id="{{$product->id}}"
                      data-product-product_id="{{$product->product_id}}"
                  >
                    <td style="max-width: 150px">{{$product->product->title}}</td>
                    <td class="justify-content-between" style="min-width: 140px ; width: 150px">
                      <button style="margin-right: 1vh" type="button"
                              class="btn btn-sm rounded-pill btn-icon btn-outline-primary"
                              onclick="updateValuesFix(this, 'decrement')">
                        <span class="tf-icons bx bx-minus"></span>
                      </button>
                      <span class="quantity"
                            style="display: inline-block; width: 22px; text-align: center;">{{$product->quantity}}</span>
                      <button style="margin-left: 1vh" type="button"
                              class="btn btn-sm rounded-pill btn-icon btn-outline-primary"
                              onclick="updateValuesFix(this, 'increment')">
                        <span class="tf-icons bx bx-plus"></span>
                      </button>
                    </td>
                    <td class="product-per-price">{{$product->product->price}}<span> TL</span></td>
                    <td class="product-total-price">{{$product->product->price * $product->quantity}}<span> TL</span></td>
                    <td>
                      <button type="button" class="btn rounded-pill btn-sm btn-icon btn-outline-danger">
                        <span class="tf-icons bx bxs-trash"></span>
                      </button>
                    </td>
                  </tr>
                @endforeach
                </tbody>
              </table>
              <div class="d-flex justify-content-end align-items-center m-6 mb-2" style="margin-top: 1vh">
                <div class="order-calculations">
                  <div class="d-flex justify-content-start">
                    <h6 class="w-px-100 mb-0">Toplam:</h6>
                    <h6 class="mb-0" id="grandTotal">0 TL</h6>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="container">
            <div class="justify-content-end d-flex gap-1">
              <button id="splitShopcart"
                      class="btn btn-secondary"
                      data-bs-toggle="modal"
                      data-bs-target="#largeModal"
                      onclick="updateDatabase()">
                Hesabı Ayır
              </button>
              <div class="btn-group">
                <button type="button" class="btn btn-warning dropdown-toggle" data-bs-toggle="dropdown"
                        aria-expanded="false">Böl
                </button>
                <ul class="dropdown-menu" style="">
                  <li><a class="dropdown-item" href="javascript:void(0);">2'ye Böl</a></li>
                  <li><a class="dropdown-item" href="javascript:void(0);">3' e Böl</a></li>
                  <li><a class="dropdown-item" href="javascript:void(0);">4' e Böl</a></li>
                </ul>
              </div>
                <button onclick="paidAll(this)" class="btn btn-success">
                  Toplam
                </button>
            </div>
          </div>
        </div>
      </div>
      <!--/END Content  -->
    </div>
    <!--/ Layout container -->
  </div>
  <!--/ Layout wrapper -->
@endsection
<script>
  let beforeunload = true; // Varsayılan olarak false
  let isUpdating = false; // Güncelleme işlemi kontrolü

  // Sayfa yenilendiğinde çalışacak olan fonksiyon
  async function onBeforeUnload(event) {
    if (beforeunload && !isUpdating) {
      isUpdating = true; // Güncelleme işlemini başlat

      // updateDatabase fonksiyonunu başlat ve bitene kadar bekle
      try {
        await updateDatabase();
        console.log('updateDatabase çalıştırıldı');
      } catch (error) {
        console.error('Error in updateDatabase:', error);
      }

      // Sayfanın yenilenmesini engelle
      event.preventDefault();

      alert('Sayfa yenileniyor, işlemler kaydedilmeden sayfayı yenilememeniz önerilir.');

    }
  }

  // Sayfa yenilendiğinde veya kapatılmadan önce çalışacak olay
  window.addEventListener('beforeunload', onBeforeUnload);

  async function updateDatabase() {
    // *********** DATABASE GÜNCELLEME SCRİPTİ *******************
    // Tüm satırları seçin
    var rows = document.querySelectorAll('tr.product-row-tr');

    // Tüm verileri tutacak bir array oluştur
    var productsArray = [];

    rows.forEach(function (row) {
      // Ürün ID'sini ve mevcut değeri alın
      var productShopcartId = row.getAttribute('data-product-shopcart-id');
      var productProductId = row.getAttribute('data-product-product_id');
      var productQuantityElement = row.querySelector('.quantity');

      var productQuantityValue = parseInt(productQuantityElement.textContent);

      // Her bir ürün için obje oluştur ve array'e ekle
      productsArray.push({
        product_shopcart_id: productShopcartId,
        product_id: productProductId,
        quantity: productQuantityValue,
      });
    });

    try {
      // AJAX çağrısı oluştur ve productsArray'i gönder
      const response = await fetch('/update-database', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
          products: productsArray
        })
      });

      const data = await response.json();

      if (data.success) {
        console.log(data);
      } else {
        console.log('error');
        console.log(data);
      }

    } catch (error) {
      console.error('Error updating products:', error);
    }

    updateTableTotals();
  }

</script>

<script>
  function updateTableTotals() {
    fetch('/update-table-totals', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({
        shopcartId:{{$shopcartId}}
      })
    })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          console.log(data);
        } else {
          console.log('error');
          console.log(data);
        }
      })
      .catch(error => {
        console.error('Error updating products:', error);
      });
  }
  document.addEventListener('DOMContentLoaded', function() {
    updateGrandTotal();
    updateTableTotals();

  });
  // *********** BUTONLARLA DEĞER ARtTIRMA AZALTMA SCRİPTİ *******************
  // BU SCRİPT AYNI ZAMANDA MODALDEKİ MAX QUANTİTYİ BELİRLİYOR
  function updateValuesFix(button, action) {
    // Button'un bulunduğu satırı bul
    var row = button.closest('tr.product-row-tr');

    var quantityElement = row.querySelector('.quantity');
    var productPerPriceElement = row.querySelector('.product-per-price');
    var productTotalPriceElement = row.querySelector('.product-total-price');
    var productId = row.getAttribute('data-product-shopcart-id');


    // Mevcut value ve tane değerlerini al
    var quantityInt = parseInt(quantityElement.textContent);
    var productPerPriceInt = parseInt(productPerPriceElement.textContent.trim());

    // Değeri artır veya azalt
    if (action === 'increment') {
      quantityInt++;
    } else if (action === 'decrement' && quantityInt > 0) {
      quantityInt--;
    }

    // Güncellenen value değerini ekrana yaz
    quantityElement.textContent = quantityInt;

    // value ile tane değerini çarp ve sonucu TL ile birlikte ekrana yaz
    var total = quantityInt * productPerPriceInt;
    productTotalPriceElement.innerHTML = total + ' <span>TL</span>';

    // Diğer span elemanlarını güncelle
    updateModalMaxQuantity(productId, quantityInt);
    updateGrandTotal();
  }

  function paidAll(button) {
    var rows = document.querySelectorAll('.product-row-tr');

    // Tüm verileri tutacak bir array oluştur
    var productsArray = [];

    rows.forEach(function (row) {
      // Ürün ID'sini ve mevcut değeri alın
      var productShopcartId = row.getAttribute('data-product-shopcart-id');
      var productProductId = row.getAttribute('data-product-product_id');
      var productQuantityElement = row.querySelector('.quantity');
      var productQuantityValue = parseInt(productQuantityElement.textContent);

      // Her bir ürün için obje oluştur ve array'e ekle
      productsArray.push({
        product_shopcart_id: productShopcartId,
        product_id: productProductId,
        quantity: productQuantityValue,
      });
    });

    fetch('/update-database-paid', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({
        products: productsArray,
        shopcart_id:{{$shopcartId}}
      })
    })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          console.log(data);
        } else {
          console.log('error');
          console.log(data);
        }
      })
      .catch(error => {
        console.error('Error updating products:', error);
      });
    updateTableTotals();
    beforeunload = !beforeunload;
    window.location.reload();
  }
  function updateModalMaxQuantity(productId, newValue) {
    // Tüm product-row-tr sınıfına sahip satırları seç
    var rows = document.querySelectorAll('tr.product-row-tr-modal');

    // Her satırda döngü yap
    rows.forEach(function(row) {
      // Satırdaki data-product-shopcart_id değerini al
      var rowProductId = row.getAttribute('data-product-shopcart_id');

      // Eğer ID'ler eşleşirse
      if (rowProductId === productId.toString()) {
        // currentValueMax class'ına sahip <span> elemanını bul
        var maxQuantityElement = row.querySelector('.maxQuantity');

        // İçeriği güncelle
        if (maxQuantityElement) {
          maxQuantityElement.textContent = newValue + ' /';
        }
      }
    });
  }
  function updateGrandTotal() {
    var shopcartLeftTotalElement = document.querySelectorAll('.product-total-price');

    // Genel toplamı hesaplamak için bir değişken tanımla
    var shopcartLeftTotalValue = 0;


    // Her bir total değeri için döngüye gir ve genel toplama ekle
    shopcartLeftTotalElement.forEach(function (totalElement) {
      // Total değerini sayıya çevir ve genel toplamı artır
      var totalValue = parseInt(totalElement.textContent);
      shopcartLeftTotalValue += totalValue;
    });

    // Genel toplamı ekrana yaz
    document.getElementById('grandTotal').innerHTML = shopcartLeftTotalValue + ' TL';
  }
</script>
