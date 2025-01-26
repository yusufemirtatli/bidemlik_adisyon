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
      <div class="container">
        <div class="col-md-12">
          <div class="card mb-4">
            <h5 class="card-header">{{$food->title}} Güncelle</h5>
            <div class="card-body demo-vertical-spacing demo-only-element">
              <form action="{{route('menu-food-detail-update',$food->id)}}" method="post" enctype="multipart/form-data">
                @csrf
                <div>
                  <label for="title" class="form-label">Ürün İsmi</label>
                  <input type="text" class="form-control" name="title" id="title" value="{{$food->title}}">
                </div>

                <div class="mb-3">
                  <label for="image" class="form-label">Ürün Görseli</label>
                  <input class="form-control" type="file" id="image" name="image">
                  @if($food->image)
                    <div class="mt-2">
                      <img src="{{ asset('storage/' . $food->image) }} " alt="Mevcut Ürün Görseli" width="150">
                    </div>
                    <input type="hidden" name="existing_image" value="{{ $food->image }}">
                  @endif
                </div>


                <div>
                  <label for="desc" class="form-label">Ürün Açıklaması</label>
                  <input type="text" class="form-control" name="desc" id="desc" value="{{$food->desc}}" >
                </div>

                <div class="mb-3">
                  <label for="category" class="form-label">Ürün Kategorisi</label>
                  <select id="category" name="category" class="form-select">
                    <option disabled>Ürün Kategorisi Seç</option>
                    @foreach($categories as $category)
                      <option value="{{$category->id}}" {{ $food->category_id == $category->id ? 'selected' : '' }}>
                        {{$category->title}}
                      </option>
                    @endforeach
                  </select>

                </div>
                <div style="margin-top: 1vh" class="row g-2">
                  <div class="col mb-0">
                    <label for="price" class="form-label">Maliyet 1 </label>
                    <input id="price" name="cost" class="form-control" type="text" rows="1" value="{{$food->cost}}">
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
                    <label for="price" class="form-label">Fiyat 1 (Görünür Fiyat) </label>
                    <input id="price" name="price" class="form-control" type="text" value="{{$food->price}}" rows="1">
                  </div>
                  <div class="col mb-0">
                    <label for="exampleFormControlTextarea1" class="form-label">Fiyat 1,5</label>
                    <input disabled id="exampleFormControlTextarea1" class="form-control" type="text" rows="1">
                  </div>
                  <div class="col mb-0">
                    <label for="exampleFormControlTextarea1" class="form-label">Fiyat 2</label>
                    <input disabled id="exampleFormControlTextarea1" class="form-control" type="text" rows="1">
                  </div>
                </div>
                <div class="form-check form-check-inline mt-3" style="padding-left: 0 !important;">
                  <label for="isListed" class="fs-3 me-3" style="margin-top: 1vh">Ürünü Menüde Listele</label>
                  <!-- iOS Style: Rounded -->
                  <style>
                    .switch.ios, .switch-on.ios, .switch-off.ios { border-radius: 20rem; }
                    .switch.ios .switch-handle { border-radius: 20rem; }
                  </style>
                  <input id="isListed" name="isListed" type="checkbox" data-toggle="switchbutton"  data-style="ios" checked data-onlabel="Evet" data-offlabel="Hayır" data-onstyle="primary" data-offstyle="danger">
                </div>
                <div class="d-flex justify-content-end">
                  <button type="submit" class="btn btn-primary mt-3">Ürünü Güncelle</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--/ Layout container -->
  </div>
  <!--/ Layout wrapper -->
@endsection

