@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard - Analytics')

@section('vendor-style')
  <link rel="stylesheet" href="{{asset('assets/vendor/libs/apex-charts/apex-charts.css')}}">
@endsection

@section('vendor-script')
  <script src="{{asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>
@endsection

@section('page-script')
  <script src="{{asset('assets/js/dashboards-analytics.js')}}"></script>
@endsection

@section('content')
  <div class="row">
    <div class="col-lg-12 col-md-6 order-1">
      <div class="row">
        <div class="col-lg-2 col-md-12 col-6 mb-4">
          <div class="card">
            <div class="card-body">
              <div class="card-title d-flex align-items-start justify-content-between">
                <div class="avatar flex-shrink-0">
                  <span class="avatar-initial rounded bg-label-warning"><i class='bx bx-credit-card'></i></span>
                </div>
              </div>
              <span class="fw-semibold d-block mb-1">Günlük<br>Ciro</span>
              @php($totalC = 0)
              @foreach($dailyAdisyon as $rs)
                @php($totalC = $rs->all_total + $totalC)
              @endforeach
              <h3 class="card-title mb-2">{{$totalC}} TL</h3>
            </div>
          </div>
        </div>
        <div class="col-lg-2 col-md-12 col-6 mb-4">
          <div class="card">
            <div class="card-body">
              <div class="card-title d-flex align-items-start justify-content-between">
                <div class="avatar flex-shrink-0">
                  <span class="avatar-initial rounded bg-label-danger"><i class='bx bx-money-withdraw'></i></span>
                </div>
              </div>
              <span class="fw-semibold d-block mb-1">Günlük Ürün Maliyeti</span>
              @php($totalM = 0)
              @foreach($dailySales as $rs)
                @php($tempProduct = \App\Models\product::find($rs->product_id))
                @php($temp = $tempProduct->cost * $rs->quantity)
                @php($totalM = $temp + $totalM)
              @endforeach
              <h3 class="card-title mb-2">{{$totalM}} TL</h3>
            </div>
          </div>
        </div>
        <div class="col-lg-2 col-md-12 col-6 mb-4">
          <div class="card">
            <div class="card-body">
              <div class="card-title d-flex align-items-start justify-content-between">
                <div class="avatar flex-shrink-0">
                  <span class="avatar-initial rounded bg-label-success"><i class='bx bx-wallet'></i></span>
                </div>
              </div>
              <span class="fw-semibold d-block mb-1">Günlük Ürün<br>Net Kar</span>
              <h3 class="card-title mb-2">{{$totalC - $totalM}} TL</h3>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-2 col-md-12 col-6 mb-4">
          <div class="card">
            <div class="card-body">
              <div class="card-title d-flex align-items-start justify-content-between">
                <div class="avatar flex-shrink-0">
                  <span class="avatar-initial rounded bg-label-warning"><i class='bx bx-credit-card'></i></span>
                </div>
              </div>
              <span class="fw-semibold d-block mb-1">Aylık<br>Ciro</span>
              @php($totalCM = 0)
              @foreach($monthlyAdisyon as $rs)
                @php($totalCM = $rs->all_total + $totalCM)
              @endforeach
              <h3 class="card-title mb-2">{{$totalCM}} TL</h3>
            </div>
          </div>
        </div>
        <div class="col-lg-2 col-md-12 col-6 mb-4">
          <div class="card">
            <div class="card-body">
              <div class="card-title d-flex align-items-start justify-content-between">
                <div class="avatar flex-shrink-0">
                  <span class="avatar-initial rounded bg-label-danger"><i class='bx bx-money-withdraw'></i></span>
                </div>
              </div>
              <span class="fw-semibold d-block mb-1">Aylık Ürün Maliyeti</span>
              @php($totalMM = 0)
              @foreach($monthlySales as $rs)
                @php($tempProduct = \App\Models\product::find($rs->product_id))
                @php($temp = $tempProduct->cost * $rs->quantity)
                @php($totalMM = $temp + $totalMM)
              @endforeach
              <h3 class="card-title mb-2">{{$totalMM}} TL</h3>
            </div>
          </div>
        </div>
        <div class="col-lg-2 col-md-12 col-6 mb-4">
          <div class="card">
            <div class="card-body">
              <div class="card-title d-flex align-items-start justify-content-between">
                <div class="avatar flex-shrink-0">
                  <span class="avatar-initial rounded bg-label-success"><i class='bx bx-wallet'></i></span>
                </div>
              </div>
              <span class="fw-semibold d-block mb-1">Aylık Ürün<br>Net Kar</span>
              <h3 class="card-title mb-2">{{$totalCM -  $totalMM}} TL</h3>
            </div>
          </div>
        </div>
        <div class="col-lg-2 col-md-12 col-6 mb-4">
          <div class="card">
            <div class="card-body">
              <div class="card-title d-flex align-items-start justify-content-between">
                <div class="avatar flex-shrink-0">
                  <span class="avatar-initial rounded bg-label-danger"><i class='bx bx-money-withdraw'></i></span>
                </div>
              </div>
              <span class="fw-semibold d-block mb-1">Aylık<br>Giderler</span>
              @php($mG = \App\Models\gider::whereBetween('created_at', [\Carbon\Carbon::now()->startOfMonth(), \Carbon\Carbon::now()->endOfMonth()])->sum('amount'))
              <h3 class="card-title mb-2">{{$mG}}TL</h3>
            </div>
          </div>
        </div>
        <div class="col-lg-2 col-md-12 col-6 mb-4">
          <div class="card">
            <div class="card-body">
              <div class="card-title d-flex align-items-start justify-content-between">
                <div class="avatar flex-shrink-0">
                  <span class="avatar-initial rounded bg-label-info"><i class='bx bxs-briefcase-alt'></i></span>
                </div>
              </div>
              <span class="fw-semibold d-block mb-1">Aylık<br>Kasa</span>
              <h3 class="card-title mb-2">{{ $totalCM - $mG}} TL</h3>
            </div>
          </div>
        </div>
      </div>
      </div>
    </div>
  </div>
  <div class="p-4">
    <div class="row mb-5">
      <!-- Order Statistics -->
      <div class="col-12 col-xl-6">
        <div class="card h-100">
          <div class="card-header d-flex align-items-center justify-content-between pb-0">
            <div class="card-title mb-0">
              <h5 class="m-0 me-2">Sipariş İstatistikleri</h5>
              <small class="text-muted">@php($qtm = 0)
                @foreach($monthlySales as $rs)
                  @php( $qtm = $rs->quantity + $qtm )
                @endforeach
                {{$qtm}} Aylık Sipariş
              </small>
            </div>
          </div>
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <div class="d-flex flex-column align-items-center gap-1">
                <h2 class="mb-2">
                  {{ \App\Models\product_shopcart::all()->sum('quantity')}}
                </h2>
                <span>Toplam Sipariş</span>
              </div>
            </div>
            <ul class="p-0 m-0">
              @foreach($categories as $category)
                <li class="d-flex mb-4 pb-1">
                  <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                    <div class="w-75">
                      <h6 class="mb-0">{{$category->title}}</h6>
                      <a class="btn btn-primary mt-1 btn-sm" data-bs-toggle="collapse" href="#{{$category->id}}" role="button" aria-expanded="false" aria-controls="collapseExample">
                        Ürünleri Gör
                      </a>
                      <div class="collapse w-100" id="{{$category->id}}">
                        <div class="table-responsive text-nowrap">
                          <table class="table table-borderless">
                            <thead>
                            <tr>
                              <th class="w-px-250"></th>
                              <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($cProducts = \App\Models\product::where('category_id',$category->id)->get())
                            @php($cPQToplam = 0)
                            @foreach($cProducts as $cProduct)
                              @php($cProductQuantity = \App\Models\product_shopcart::where('product_id',$cProduct->id)->sum('quantity'))
                              <tr>
                                <td class="mt-0">{{$cProduct->title}}</td>
                                <td><span>{{$cProductQuantity}}</span> <span class="ms-3">Günlük Ort. : {{$cProductQuantity / $diffDays}}</span></td>
                              </tr>
                              @php($cPQToplam += $cProductQuantity)
                            @endforeach
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                    <div class="user-progress">
                      <small class="fw-medium">{{$cPQToplam}} </small>
                    </div>
                  </div>
                </li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="card">
      <h5 class="card-header">Günlük Özet</h5>
      <div class="table-responsive text-nowrap">
        <table class="table table-striped">
          <thead>
          <tr>
            <th>Tarih</th>
            <th>Brüt Kar</th>
            <th>Adisyon Adedi</th>
            <th>Adisyonları Gör</th>
            <th>Günü Kapat</th>
          </tr>
          </thead>
          <tbody class="table-border-bottom-0">
          @foreach ($monthlyAdisyonByDay as $day => $adisyons)
            <!-- Table Row -->
            <tr>
              <td><span class="fw-medium">{{ \Carbon\Carbon::parse($day)->translatedFormat('d F') }}</span></td>
              <td>{{ $adisyons->sum('all_total') }} TL</td>
              <td class="ps-5">{{$adisyons->count()}}</td>
              <td>
                <button type="button" class="btn rounded-pill btn-info" data-bs-toggle="modal" data-bs-target="#modal-{{ \Illuminate\Support\Str::slug($day) }}">
                  Adisyonları Görüntüle
                </button>
              </td>
              <td>
                <a href="{{ route('export.adisyons.excel', ['day' => $day]) }}" class="btn rounded-pill btn-success">
                  Günü Kapat
                </a>
              </td>
            </tr>
          @endforeach
          </tbody>
        </table>
        <!-- Modallar Adisyon -->
        @foreach ($monthlyAdisyonByDay as $day => $adisyons)
          <div class="modal fade" id="modal-{{ \Illuminate\Support\Str::slug($day) }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Adisyonlar - {{ \Carbon\Carbon::parse($day)->translatedFormat('d F Y') }}</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="card">
                    <div class="table-responsive text-nowrap">
                      <table class="table table-striped">
                        <thead>
                        <tr>
                          <th>Adisyon Numarası</th>
                          <th>Masa İsmi</th>
                          <th>Adisyon Tutarı</th>
                          <th>Satılan Ürünler</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($adisyons as $index => $adisyon)
                          @php($uniqueIndex = \Illuminate\Support\Str::slug($day) . '-' . $index)
                          <tr>
                            <td>{{ $adisyon->id }}</td> <!-- Adisyon Numarası -->
                            <td>
                              @php($table = \App\Models\tables::find($adisyon->table_id))
                              {{ $table->title }}
                            </td> <!-- Masa İsmi -->
                            <td>{{ $adisyon->all_total }} TL</td> <!-- Adisyon Tutarı -->
                            <td>
                              <!-- Akordiyon Tetikleyici -->
                              <button type="button" class="btn btn-info" onclick="toggleAccordion('{{ $uniqueIndex }}')">
                                Satılan Ürünler
                              </button>
                            </td>
                          </tr>

                          <!-- Akordiyon İçeriği -->
                          <tr id="accordion-row-{{ $uniqueIndex }}" style="display: none;">
                            <td colspan="4">
                              <div class="card">
                                <div class="card-body">
                                  <div class="table-responsive text-nowrap">
                                    <table class="table table-hover">
                                      <thead>
                                      <tr>
                                        <th>Ürün Adı</th>
                                        <th>Miktar</th>
                                        <th>Fiyat Tane</th>
                                        <th>Fiyat Toplam</th>
                                      </tr>
                                      </thead>
                                      <tbody>
                                      @php($soldProducts = \App\Models\product_shopcart::where('shopcart_id', $adisyon->id)->where('isPaid',1)->get())
                                      @foreach ($soldProducts as $product)
                                        @php($pt = \App\Models\product::where('id',$product->product_id)->first())
                                        @if($pt)
                                          <tr>
                                            <td>{{ $pt->title }}</td>
                                            <td>{{ $product->quantity }}</td>
                                            <td>{{ $pt->price }} TL</td>
                                            <td>{{ $pt->price * $product->quantity}} TL</td>
                                          </tr>
                                        @else
                                          <tr>
                                            <td>Silinen Ürün</td>
                                            <td>{{ $product->quantity }}</td>
                                            <td>Silinen Fiyat</td>
                                            <td>Silinen Fiyat</td>
                                          </tr>
                                        @endif

                                      @endforeach
                                      </tbody>
                                    </table>
                                  </div>
                                </div>
                              </div>
                            </td>
                          </tr>
                        @endforeach
                        </tbody>
                      </table>

                      <script>
                        function toggleAccordion(index) {
                          const row = document.getElementById(`accordion-row-${index}`);
                          if (row.style.display === "none") {
                            row.style.display = "table-row";
                          } else {
                            row.style.display = "none";
                          }
                        }
                      </script>

                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>

    <div class="card mt-4">
      <h5 class="card-header">Aylık Özet</h5>
      <div class="table-responsive text-nowrap">
        <table class="table table-striped">
          <thead>
          <tr>
            <th>Ay</th>
            <th>Ciro</th>
            <th>Giderler</th>
            <th>Net Kar</th>
            <th>Aylık Rapor</th>
          </tr>
          </thead>
          <tbody class="table-border-bottom-0">
          @foreach($monthlyAdisyonByMonth as $month => $mAdisyon)
            @php($year = explode('-', $month)[0])
            @php($monthNumber = explode('-', $month)[1])
            @php($monthlyGider = \App\Models\Gider::whereYear('created_at', $year)->whereMonth('created_at', $monthNumber)->sum('amount'))
            <tr>
              <td>{{ \Carbon\Carbon::parse($month)->translatedFormat('F Y') }}</td>
              <td>{{$mAdisyon->sum('all_total')}}</td>
              <td>{{$monthlyGider}}</td>
              <td>{{$mAdisyon->sum('all_total') - $monthlyGider}}</td>
              <td><a href="{{ route('export.adisyons.excel.month', ['month' => $month]) }}" class="btn rounded-pill btn-success">
                  Aylık Rapor İndir
                </a></td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection
