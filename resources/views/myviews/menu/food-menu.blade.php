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
        <div class="d-flex justify-content-end flex-row gap-2" style="margin-bottom: 1.5vh">
          <div class="d-flex btn btn-info" data-bs-toggle="modal" data-bs-target="#myModal">Ekle</div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <form action="{{route('add_product')}}" method="post" enctype="multipart/form-data">
              @csrf
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Ürün Ekle</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="card mb-4">
                  <div class="card-body">
                    <div class="form-floating">
                      <input type="text" class="form-control" id="title" name="title" aria-describedby="floatingInputHelp">
                      <label for="floatingInput">Ürün Adı</label>
                    </div>
                    <div style="margin-top: 1vh" class="mb-3">
                      <label for="image" class="form-label">Ürün Görseli</label>
                      <input class="form-control" type="file" id="image" name="image">
                    </div>
                    <div>
                      <label for="desc" class="form-label">Ürün Açıklaması</label>
                      <input id="desc" name="desc" class="form-control" type="text">
                    </div>
                    <div class="mb-3" style="margin-top: 2vh">
                      <label for="category_id" class="form-label">Ürün Kategorisi</label>
                      <select id="category_id" name="category_id" class="form-select">
                        <option>Ürün Kategorisi Seç</option>
                        @foreach($categories as $category)
                          <option value="{{$category->id}}">{{$category->title}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div style="margin-top: 1vh" class="row g-2">
                      <div class="col mb-0">
                        <label for="cost" class="form-label">Maliyet 1 </label>
                        <input id="cost" name="cost" class="form-control" type="text" rows="1">
                      </div>
                      <div class="col mb-0">
                        <label for="cost1,5" class="form-label">Maliyet 1,5</label>
                        <input disabled id="cost1,5" class="form-control" type="text" rows="1">
                      </div>
                      <div class="col mb-0">
                        <label for="cost2" class="form-label">Maliyet 2</label>
                        <input disabled id="cost2" class="form-control" type="text" rows="1">
                      </div>
                    </div>
                    <div style="margin-top: 1vh" class="row g-2">
                      <div class="col mb-0">
                        <label for="price" class="form-label">Fiyat 1</label>
                        <input id="price" name="price" class="form-control" type="text" placeholder="Görünür Fiyat">
                      </div>
                      <div class="col mb-0">
                        <label for="exampleFormControlTextarea1" class="form-label">Fiyat 1,5</label>
                        <input disabled id="exampleFormControlTextarea1" class="form-control" type="text">
                      </div>
                      <div class="col mb-0">
                        <label for="exampleFormControlTextarea1" class="form-label">Fiyat 2</label>
                        <input disabled id="exampleFormControlTextarea1" class="form-control" type="text">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary">Ürünü Ekle</button>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="row mb-5">
          <h3>Menüde Olan Ürünler</h3>
          @foreach($listproducts as $product)
            <div class="col-md-6">
              <div class="card mb-3 position-relative">
                <div class="row g-0">
                  <div class="col-md-4">
                    <img class="card-img card-img-left" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}" style="width: 100%; height: 100%;">
                  </div>
                  <div class="col-md-8">
                    <div class="card-body">
                      <h5 class="card-title">{{$product->title}}</h5>
                      <p class="card-text">
                        {{$product->desc}}
                      </p>
                      <div class=" flex-row d-flex w-100">
                        @php
                          $p_cat = \App\Models\category::find($product->category_id);
                        @endphp
                        <p class="card-text w-75"><small class="text-muted">@if($p_cat) {{$p_cat->title}}@else Silinmiş Kategori Lütfen Yeni Kategori Atayın @endif</small></p>
                        <div class="justify-content-end w-25 flex-row d-flex" style="place-items: center">
                          <div class="d-flex align-items-center">
                            <h4 class="text-center mb-0" style="color: black">{{$product->price}}</h4>
                            <img src="/assets/img/icons/payments/turkish-lira (1).png" alt="Lira icon" style="width: 15px;margin-left: 2px">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Edit butonu, sağ üst köşede kalem ikonu ile -->
                <a href="{{route('menu-food-detail',$product->id)}}"><button class="btn btn-primary position-absolute btn-sm"
                                                                             style="top: 5px; right: 5px;">
                    <i class="bx bxs-edit" style="font-size: 1rem"></i>
                  </button></a>
              </div>
            </div>
          @endforeach
          @if($unlistproducts->isNotEmpty())
            <h3>Menüde Olmayan Ürünler</h3>
          @endif
          @foreach($unlistproducts as $product)
            <div class="col-md-6">
              <div class="card mb-3 position-relative">
                <div class="row g-0">
                  <div class="col-md-4">
                    <img class="card-img card-img-left" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}" style="width: 100%; height: 100%;">,
                  </div>
                  <div class="col-md-8">
                    <div class="card-body">
                      <h5 class="card-title">{{$product->title}}</h5>
                      <p class="card-text">
                        {{$product->desc}}
                      </p>
                      <div class=" flex-row d-flex w-100">
                        @php
                          $p_cat = \App\Models\category::find($product->category_id);
                        @endphp
                        <p class="card-text w-75"><small class="text-muted">@if($p_cat) {{$p_cat->title}}@else Silinmiş Kategori Lütfen Yeni Kategori Atayın @endif</small></p>
                        <div class="justify-content-end w-25 flex-row d-flex" style="place-items: center">
                          <div class="d-flex align-items-center">
                            <h4 class="text-center mb-0" style="color: black">{{$product->price}}</h4>
                            <img src="/assets/img/icons/payments/turkish-lira (1).png" alt="Lira icon" style="width: 15px;margin-left: 2px">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Edit butonu, sağ üst köşede kalem ikonu ile -->
                <a href="{{route('menu-food-detail',$product->id)}}"><button class="btn btn-primary position-absolute btn-sm"
                                                                             style="top: 5px; right: 5px;">
                    <i class="bx bxs-edit" style="font-size: 1rem"></i>
                  </button></a>
              </div>
            </div>
          @endforeach
        </div>
      </div>
      <!--/END Content  -->
    </div>
    <!--/ Layout container -->
  </div>
  <!--/ Layout wrapper -->
@endsection
