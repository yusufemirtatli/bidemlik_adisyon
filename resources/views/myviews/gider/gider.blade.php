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
  @php($months = [])
  @for($i = 0; $i < 3; $i++)
    @php($months[] = \Carbon\Carbon::now()->subMonths($i)->format('Y-m'))
  @endfor

  @foreach ($months as $month)
    @php($monthlyTotal = 0) <!-- Initialize monthly total -->
    <div class="card mb-4">
      <h5 class="card-header">{{ \Carbon\Carbon::parse($month)->translatedFormat('F Y') }} Giderleri</h5>
      <div class="table-responsive text-nowrap">
        <table class="table table-striped">
          <thead>
          <tr>
            <th>Gider İsmi</th>
            <th>Gider Adedi</th>
            <th>Gider Tutarı</th>
            <th>Detaylar</th>
          </tr>
          </thead>
          <tbody>
          @foreach($giderCategory as $cat)
            @php($giderAdet = \App\Models\gider::where('cat_id', $cat->id)
                                            ->whereBetween('created_at', [
                                                \Carbon\Carbon::parse($month)->startOfMonth(),
                                                \Carbon\Carbon::parse($month)->endOfMonth()
                                            ])
                                            ->count())
            @php($giderToplam = \App\Models\gider::where('cat_id', $cat->id)
                                              ->whereBetween('created_at', [
                                                  \Carbon\Carbon::parse($month)->startOfMonth(),
                                                  \Carbon\Carbon::parse($month)->endOfMonth()
                                              ])
                                              ->sum('amount'))

            @php($monthlyTotal += $giderToplam) <!-- Add each category's total to the monthly total -->

            <tr>
              <td>{{$cat->name}}</td>
              <td>{{$giderAdet}}</td>
              <td>{{$giderToplam}} TL</td>
              <td>
                <!-- Akordiyon Tetikleyici -->
                <button type="button" class="btn btn-info" onclick="toggleAccordion('{{$month}}', {{$cat->id}})">
                  Detaylar
                </button>
              </td>
            </tr>

            <!-- Akordiyon İçeriği -->
            <tr id="accordion-row-{{$month}}-{{$cat->id}}" style="display: none;">
              <td colspan="4">
                <div class="card">
                  <div class="d-flex justify-content-end">
                    <button class="btn btn-success me-3 mt-3" id="open-modal" data-bs-toggle="modal" data-bs-target="#modal-create-{{$month}}-{{$cat->id}}">Gider Ekle</button>
                  </div>
                  <div class="card-body" style="padding-top: 2vh !important;">
                    <div class="table-responsive text-nowrap">
                      <table class="table table-hover">
                        <thead>
                        <tr>
                          <th>Gider Detayı</th>
                          <th>Gider Tutarı</th>
                          <th>Eklenme Tarihi</th>
                          <th class="ps-4">Sil</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php($giderler = \App\Models\gider::where('cat_id', $cat->id)
                                                        ->whereBetween('created_at', [
                                                            \Carbon\Carbon::parse($month)->startOfMonth(),
                                                            \Carbon\Carbon::parse($month)->endOfMonth()
                                                        ])
                                                        ->get())
                        @if(isset($giderler) && $giderler->isNotEmpty())
                          @foreach($giderler as $gider)
                            <tr>
                              <td>{{$gider->name}}</td>
                              <td>{{$gider->amount}} TL</td>
                              <td>{{ $gider->created_at->format('d.m.Y') }}</td>
                              <td>
                                <form action="{{route('delete-gider',$gider->id)}}" method="POST">
                                  @csrf
                                  @method('POST')
                                  <button type="submit" class="btn btn-danger">Sil</button>
                                </form>
                              </td>
                            </tr>
                          @endforeach
                        @else
                          <tr>
                            <td colspan="5" class="text-center">Herhangi bir gider bulunamadı.</td>
                          </tr>
                        @endif
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </td>
            </tr>
            <!-- Modallar Adisyon -->
            <div class="modal fade" id="modal-create-{{$month}}-{{$cat->id}}" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <form action="{{route('gider-add')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                      <h5 class="modal-title">Gider Ekle {{$cat->name}}</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <input name="date" type="hidden" value="{{$month}}">
                      <div class="card">
                        <div class="card-body">
                          <input type="hidden" name="category" value="{{$cat->id}}">
                          <div class="mb-2">
                            <label for="name" class="form-label">Gider İsmi</label>
                            <input type="text" class="form-control" id="name" name="name">
                          </div>
                          <div class="mb-2">
                            <label for="amount" class="form-label">Gider Tutarı</label>
                            <input type="text" class="form-control" id="amount" name="amount">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-primary" onclick="console.log('Form Gönderildi!')">Kaydet</button>
                      <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          @endforeach
          </tbody>
          <caption class="ms-4">Toplam Tutar : {{$monthlyTotal}} TL</caption> <!-- Display monthly total -->
        </table>
      </div>
    </div>
  @endforeach

  <script>
    function toggleAccordion(month, index) {
      const row = document.getElementById(`accordion-row-${month}-${index}`);
      if (row.style.display === "none") {
        row.style.display = "table-row";
      } else {
        row.style.display = "none";
      }
    }

    function openModal(categoryId, month) {
      // Açılacak modalın ID'sini konsola yazdır
      console.log('Açılan Modal ID: modal-create-' + month + '-' + categoryId);

      // Modal'ı seç ve aç
      var modalId = '#modal-create-' + month + '-' + categoryId;
      $(modalId).modal('show');  // Bootstrap modalını açmak için 'show' metodunu kullan

      // Eğer başka işlemler eklemek istersen, örneğin form verilerini doldurmak:
      // $(modalId + ' form input[name="category"]').val(categoryId);
    }

  </script>
@endsection


