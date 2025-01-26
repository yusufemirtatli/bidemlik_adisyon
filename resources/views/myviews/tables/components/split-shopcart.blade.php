<!-- Large Modal HESANI AYIR -->
<div class="modal fade" id="largeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel3">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="card" style="margin-bottom: 15px">
          <div class="d-flex flex-row justify-content-between">
            <h5 class="card-header d-flex">Adisyon Toplam</h5>
          </div>
          <div class="table-responsive text-nowrap">
            <table class="table table-striped">
              <thead>
              <tr>
                <th>Ürün</th>
                <th style="text-align: left; padding-left: 5vh">Miktar</th>
                <th>Tane Fiyatı</th>
                <th>Toplam Fiyat</th>
              </tr>
              </thead>
              <tbody class="table-border-bottom-0">
              @foreach($products as $product)
                <tr class="product-row-tr-modal" data-product-shopcart_id="{{$product->id}}" data-product-product_id="{{$product->product_id}}">
                  <td style="max-width: 150px">{{$product->product->title}}</td>
                  <td class="justify-content-between" style="width: 180px">
                    <button
                      style="margin-right: 1vh"
                      type="button"
                      class="btn btn-sm rounded-pill btn-icon btn-outline-primary"
                      onclick="updateValuesModalFix(this, 'decrement')"
                    >
                      <span class="tf-icons bx bx-minus"></span>
                    </button>
                    <div style="display: inline-block; text-align: center; width: 55px;">
                      <span class="maxQuantity"
                            data-product-shopcart-shopcart_id="{{$product->shopcart_id}}"
                            data-product-shopcart-product_id="{{$product->product_id}}">{{$product->quantity}} /</span>
                      <span class="product-quantity" style="display: inline-block; min-width: 20px; text-align: center;">0</span>
                    </div>
                    <button
                      style="margin-left: 1vh"
                      type="button"
                      class="btn btn-sm rounded-pill btn-icon btn-outline-primary"
                      onclick="updateValuesModalFix(this, 'increment')"
                    >
                      <span class="tf-icons bx bx-plus"></span>
                    </button>
                  </td>
                  <td class="product-per-price">{{$product->product->price}}<span> TL</span></td>
                  <td class="product-total-price-modal">0<span> TL</span></td>
                </tr>
              @endforeach
              </tbody>
            </table>
            <div class="d-flex justify-content-end align-items-center m-6 mb-2" style="margin-top: 1vh">
              <div class="order-calculations">
                <div class="d-flex justify-content-start">
                  <h6 class="w-px-100 mb-0">Toplam:</h6>
                  <h6 class="mb-0" id="grandTotalModal">0 TL</h6>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal" onclick="refund(this)">İade</button>
        <button type="button" class="btn btn-success" onclick="paid(this)">Ödendi</button>
      </div>
    </div>
  </div>
</div>

<script>
  // HESABI AYIR SCRİPTİ
  function updateValuesModalFix(button, action) {
    // Button'un bulunduğu satırı bul
    var row = button.closest('tr.product-row-tr-modal');
    // İlgili elementleri seç
    var maxQuantityElement = row.querySelector('.maxQuantity')
    var quantityElement = row.querySelector('.product-quantity');
    var productPerPriceElement = row.querySelector('.product-per-price');
    var totalElement = row.querySelector('.product-total-price-modal');

    // Mevcut value ve tane değerlerini al
    var quantityValue = parseInt(quantityElement.textContent);
    var productPerPriceValue = parseInt(productPerPriceElement.textContent.trim());
    var maxQuantityValue = parseInt(maxQuantityElement.textContent);
    console.log(maxQuantityValue);

    // Değeri artır veya azalt
    if (action === 'increment' && quantityValue < maxQuantityValue) {
      quantityValue++;
    } else if (action === 'decrement' && quantityValue > 0) {
      quantityValue--;
    }

    // Güncellenen value değerini ekrana yaz
    quantityElement.textContent = quantityValue;

    // value ile tane değerini çarp ve sonucu TL ile birlikte ekrana yaz
    var total = quantityValue * productPerPriceValue;
    totalElement.innerHTML = total + ' <span>TL</span>';
    updateGrandTotalModal();
  }
  function updateGrandTotalModal(){
    var shopcartSplitTotalElement = document.querySelectorAll('.product-total-price-modal');

    // Genel toplamı hesaplamak için bir değişken tanımla
    var shopcartSplitTotalValue = 0;


    // Her bir total değeri için döngüye gir ve genel toplama ekle
    shopcartSplitTotalElement.forEach(function (totalElement) {
      // Total değerini sayıya çevir ve genel toplamı artır
      var totalValue = parseInt(totalElement.textContent);
      shopcartSplitTotalValue += totalValue;
    });

    // Genel toplamı ekrana yaz
    document.getElementById('grandTotalModal').innerHTML = shopcartSplitTotalValue + ' TL';
  }
  function updateTableTotals() {
    fetch('/update-table-totals', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({
        shopcartId:{{$shopcartId}},
      })
    })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          if(data.redirect_url){
            window.location.href = data.redirect_url;
          }
        } else {
          console.log('error');
          console.log(data);
        }
      })
      .catch(error => {
        console.error('Error updating products:', error);
      });
  }
  function paid(button){
    var rows = document.querySelectorAll('.product-row-tr-modal');

    // Tüm verileri tutacak bir array oluştur
    var productsArray = [];

    rows.forEach(function (row) {
      // Ürün ID'sini ve mevcut değeri alın
      var productShopcartId = row.getAttribute('data-product-shopcart_id');
      var productProductId = row.getAttribute('data-product-product_id');
      var productQuantityElement = row.querySelector('.product-quantity');
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
  function refund(button){
    var rows = document.querySelectorAll('.product-row-tr-modal');

    // Tüm verileri tutacak bir array oluştur
    var productsArray = [];

    rows.forEach(function (row) {
      // Ürün ID'sini ve mevcut değeri alın
      var productShopcartId = row.getAttribute('data-product-shopcart_id');
      var productQuantityElement = row.querySelector('.product-quantity');
      var productQuantityValue = parseInt(productQuantityElement.textContent);

      // Her bir ürün için obje oluştur ve array'e ekle
      productsArray.push({
        product_shopcart_id: productShopcartId,
        quantity: productQuantityValue,
      });
    });

    fetch('/update-database-refund', {
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
</script>

