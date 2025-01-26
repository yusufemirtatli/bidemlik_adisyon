<style>
    .position-relative {
        position: relative;
    }

    .half-overlay {
        position: absolute;
        top: 0;
        bottom: 0;
        width: 50%;
        height: 100%;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(0, 0, 0, 0); /* Başlangıçta saydam */
        transition: background-color 0.3s ease; /* Geçiş efekti */
    }

    .half-overlay:hover {
        background-color: rgba(0, 0, 0, 0.2); /* Hover sırasında biraz karart */
    }

    .left {
        left: 0;
        padding-right: 4vh;
    }

    .right {
        right: 0;
        padding-left: 4vh;
    }

    .icon {
        font-size: 2rem; /* İkon boyutu */
        color: rgba(255, 255, 255, 0.5); /* Yarı şeffaf beyaz renk */
    }
</style>

<!--Extra Large Modal -->
<div class="modal fade" id="addprod" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addprod">Ürün Ekle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body flex-row d-flex">
                <div class="col-12">
                    <div class="nav-align-top mb-4">
                        <ul class="nav nav-pills mb-3" role="tablist">
                            @foreach($categories as $index => $category)
                                <li class="nav-item" role="presentation">
                                    <button type="button"
                                            class="nav-link {{ $loop->first ? 'active' : '' }}"
                                            role="tab"
                                            data-bs-toggle="tab"
                                            data-bs-target="#{{$category->title}}"
                                            aria-controls="{{$category->title}}"
                                            aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                                            tabindex="-1">
                                        {{$category->title}}
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                        <div class="tab-content">
                            @foreach($categories as $index => $category)
                                <div class="tab-pane fade {{ $loop->first ? 'active show' : '' }}"
                                     id="{{$category->title}}" role="tabpanel">
                                    <div class="container">
                                        <div class="row">
                                            @php
                                                $products = \App\Models\product::where('category_id', $category->id)->get();
                                            @endphp
                                            @foreach($products as $product)
                                                <!-- Eğer quantity 0'dan büyükse inputları göster -->
                                                <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 d-flex mb-3">
                                                    <div class="card border-0 text-white w-100 h-100 position-relative"
                                                         style="
                                       background-image: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0,0.3)), url({{ asset('storage/' . $product->image) }});
                                       background-size: cover;
                                       background-position: center;
                                       background-repeat: no-repeat;
                                       height: 100%;
                                       width: 100%;
                                       border: 1px solid black;
                                       border-radius: 8px;
                                     ">
                                                        <!-- Kartın içeriği flex olarak ortalanmış durumda -->
                                                        <div class="d-flex flex-column justify-content-between h-100 text-center">
                                                            <!-- Üstteki başlık -->
                                                            <div class="d-flex justify-content-center"
                                                                 style="margin-bottom: 1.5vh;padding-top: 0.8vh">
                                                                <h6 style="color: white">{{$product->title}}</h6>
                                                            </div>
                                                            <div class="card-body"></div>
                                                            <!-- Alt kısımda sayılar -->
                                                            <div class="d-flex justify-content-center"
                                                                 style="color: white; font-size: 22px; font-weight: 500; padding-top: 1vh;"
                                                                 id="number-display-{{$product->id}}">0
                                                            </div>

                                                        </div>
                                                        <!-- Sağ ve sol yarılar için tıklama alanları ve ikonlar -->
                                                        <a href="javascript:void(0);"
                                                           class="half-overlay left"
                                                           onclick="decreaseNumber('number-display-{{$product->id}}',{{$product->id}})">
                                                            <i class='bx bx-minus icon'></i>
                                                        </a>
                                                        <a href="javascript:void(0);"
                                                           class="half-overlay right"
                                                           onclick="increaseNumber('number-display-{{$product->id}}',{{$product->id}})">
                                                            <i class='bx bx-plus icon'></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" onclick="saveToDatabase()">Kaydet</button>
            </div>
        </div>
        </form>
    </div>
</div>

<script>
    // ÜRÜN EKLEME SCRİPTİ
    let globalValue = 0;
    let allArray = []; // All array olarak tanımlandı ve 0'dan başlamalı

    function updateArray(productId, newValue) {
        // Find if the productId already exists in allArray
        let productIndex = allArray.findIndex(item => item[0] === productId);

        if (productIndex !== -1) {
            if (newValue === 0) {
                // Eğer yeni değer 0 ise, bu ürünü allArray'den çıkar
                allArray.splice(productIndex, 1);
            } else {
                // Eğer mevcutsa, güncelle
                allArray[productIndex][1] = newValue;
            }
        } else if (newValue > 0) {
            // Yeni ürün ekle
            allArray.push([productId, newValue]);
        }
    }

    function increaseNumber(displayId, productId) {
        var display = document.getElementById(displayId);
        var currentValue = parseInt(display.textContent);

        // Yeni değerleri ayarlayın
        display.textContent = currentValue + 1;
        globalValue = currentValue + 1;

        // allArray'i güncelle
        updateArray(productId, globalValue);

        console.log(allArray);
    }

    function decreaseNumber(displayId, productId) {
        var display = document.getElementById(displayId);
        var currentValue = parseInt(display.textContent);

        if (currentValue > 0) { // Sayı 0'dan küçük olmasın
            display.textContent = currentValue - 1;
            globalValue = currentValue - 1;

            // allArray'i güncelle
            updateArray(productId, globalValue);

            console.log(allArray);
            console.log(dynamicValue);
        }
    }

    // Veritabanına kaydetmek için AJAX fonksiyonu
    function saveToDatabase() {
        $.ajax({
            url: '/update-product-shopcart', // Laravel rotası
            method: 'POST',
            data: {
                array: allArray,
                table: dynamicValue,
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                console.log(response);
            },
            error: function (xhr) {
                console.log(xhr.responseText);
            }
        });
        beforeunload = !beforeunload;
        window.location.reload();
    }
</script>
<script>
    // URL'den dinamik kısmı çekme
    function getDynamicPart() {
        // Mevcut URL'yi al
        const url = window.location.href;

        // URL'yi "/" karakterine göre parçala
        const parts = url.split('/');

        // Son parçayı al (bu genellikle dinamik kısımdır)
        return parts[parts.length - 1];
    }

    // Dinamik kısmı al ve kullan
    const dynamicValue = getDynamicPart();
    console.log(dynamicValue); // Örneğin: 1
</script>



