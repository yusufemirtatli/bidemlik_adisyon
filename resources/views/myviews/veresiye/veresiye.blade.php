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
        <div class="card mb-4 mt-2">
          <div class="card-header">
            <h3>Veresiye Listesi</h3>
          </div>
          <div class="card-body">
            @if(session('success'))
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            @endif

            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                <tr>
                  <th>#</th>
                  <th>Masa Numarası</th>
                  <th class="w-25">Açıklama</th>
                  <th>Ödenecek Tutar</th>
                  <th>Veresiye Tarihi</th>
                  <th class="ps-4">Adisyon</th>
                </tr>
                </thead>
                <tbody>
                @foreach($veresiyeler as $veresiye)
                  <tr>
                    <td>{{$loop->iteration}}</td>
                    @php($shopcart = \App\Models\shopcart::find($veresiye->shopcart_id))
                    @php($table = \App\Models\tables::find($shopcart->table_id))
                    <td>{{$table->title}}</td>
                    <td>{{$veresiye->description}}</td>
                    <td>{{$veresiye->left_total}} TL</td>
                    <td>{{$veresiye->created_at}}</td>
                    <td>
                      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#adisyonModal-{{$veresiye->id}}">Görüntüle</button>
                    </td>
                  </tr>
                @endforeach
                </tbody>
              </table>
              @foreach($veresiyeler as $veresiye)
                @php($shopcart = \App\Models\shopcart::find($veresiye->shopcart_id))
                @php($table = \App\Models\tables::find($shopcart->table_id))
                <!-- Modal 1 -->
                <div class="modal fade" id="adisyonModal-{{$veresiye->id}}" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Adisyon - {{$table->title}} - {{$veresiye->description}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
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
                          @php($products = \App\Models\product_shopcart::where('shopcart_id',$veresiye->shopcart_id)->get())
                          @foreach($products as $product)
                            <tr>
                              @php($p = \App\Models\product::find($product->product_id))
                              <td>{{$p->title}}</td>
                              <td>{{$product->quantity}}</td>
                              <td>{{$p->price}} TL</td>
                              <td>{{$p->price * $product->quantity}} TL</td>
                            </tr>
                          @endforeach
                          </tbody>
                        </table>
                        <div class="row mt-4 ps-3 pe-3">
                          <div class="text-start  w-50">
                            <h6>Toplam Adisyon Tutarı : <strong>{{$shopcart->all_total}} TL</strong></h6>
                          </div>
                          <div class="text-end  w-50">
                            <h6>Tahsil Edilecek Adisyon Tutarı : <strong class="text-danger">{{$veresiye->left_total}} TL</strong></h6>
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <form action="{{route('veresiye-tahsil',$veresiye->shopcart_id)}}" method="post">
                          @csrf
                          @method('POST')
                          <button type="submit" class="btn btn-primary">Tahsil Et</button>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach
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
