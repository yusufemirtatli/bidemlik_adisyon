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

        <div class="d-flex justify-content-end flex-row gap-2">
          <div class="d-flex btn btn-info" data-bs-toggle="modal" data-bs-target="#myModal">Ekle</div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <form action="{{route('add-gider-category')}}" method="post" enctype="multipart/form-data">
              @csrf
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Gider Kategori Ekleme</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="card-body">
                    <div>
                      <label for="name" class="form-label">Kategori Adi</label>
                      <input type="text"
                             class="form-control"
                             name="name"
                             id="name"
                      >
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
              </div>
            </form>
          </div>
        </div>
        <!-- Modal -->

        <div class="card mb-4 mt-2">
          <div class="card-header">
            <h3>Gider Kategorileri</h3>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                <tr>
                  <th>#</th>
                  <th>Kategori Adı</th>
                  <th>Eklenme Tarihi</th>
                  <th style="padding-left: 38px">Sil</th>
                </tr>
                </thead>
                <tbody>
                @foreach($giderCategory as $rs)
                  <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$rs->name}}</td>
                    <td>{{$rs->created_at}}</td>
                    <td>
                      <form action="{{route('delete-gider-category',$rs->id)}}" method="POST">
                        @csrf
                        @method('POST')
                        <button type="submit" class="btn rounded-pill btn-danger">Sil</button>
                      </form>

                    </td>
                  </tr>
                @endforeach
                </tbody>
              </table>
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
