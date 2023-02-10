@extends('layouts.app')

@section('content')
    <div class="container-fluid tm-container-content tm-mt-60">
        <div class="row mb-4">
            <form class="d-flex justify-content-center" id="filter-form">
                <input class="tm-search-input filter-input" type="text" id="min-price" placeholder="Min price"
                       aria-label="Search">
                <input class="tm-search-input filter-input" type="text" id="max-price" placeholder="Max price"
                       aria-label="Search">
                <input class="tm-search-input filter-input" type="text" id="keyword" placeholder="Keyword"
                       aria-label="Search">
                <input class="btn btn-primary filter-input-btn " id="filter-input-btn"
                      value="{{ __('Filter') }}" >
            </form>
        </div>

        <div class="row mb-4">
            <h2 class="col-6 tm-text-primary">
                Dishes
            </h2>
            <div class="col-6 d-flex justify-content-end align-items-center">
                <form action="" class="tm-text-primary">
                    Page <input type="text" value="1" size="1" class="tm-input-paging tm-text-primary"> of 200
                </form>
            </div>
        </div>
        <div id="main-catalog">
        <div class="row tm-mb-90 tm-gallery" id="catalog">

        </div> <!-- row -->
        <div class="row tm-mb-90">
            <div class="col-12 d-flex justify-content-between align-items-center tm-paging-col">
                <a href="javascript:void(0);" class="btn btn-primary tm-btn-prev mb-2 disabled">Previous</a>
                <div class="tm-paging d-flex">
                    {{--                    <a href="javascript:void(0);" class="active tm-paging-link">1</a>--}}
                    {{--                    <a href="javascript:void(0);" class="tm-paging-link">2</a>--}}
                    {{--                    <a href="javascript:void(0);" class="tm-paging-link">3</a>--}}
                    {{--                    <a href="javascript:void(0);" class="tm-paging-link">4</a>--}}
                </div>
                <a href="javascript:void(0);" class="btn btn-primary tm-btn-next">Next Page</a>
            </div>
        </div>
        </div>
    </div>


    <script type="module">

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
        });

        let user_id = $('#user-edit').attr('data-id');

        function checkImages(images) {
            let previewImage = '';
            let mainImage = '';

            $.each(images, function (i, image) {
                if (image['type_id'] == '0') {
                    previewImage = image['image']
                } else if (image['type_id'] == '1') {
                    mainImage = image['image']
                }
            })
            return {"previewImage": previewImage, "mainImage": mainImage}
        }

        function catalog(data) {
            $.each(data, function (i, item) {

                item['created_at'] = new Date(item['created_at']).toLocaleDateString("en-US", {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                });

                let images = checkImages(item['get_dish_images']);// return of this function  variables
                let previewImage = images["previewImage"];
                let row = `<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 mb-5">
<div class="hidden dish-author-id" data-id="${item['user_id']}"></div>
<div class="hidden dish-id" data-id=${item['id']}"></div>
                   <figure class="effect-ming tm-video-item" style="height: 209px; width: 372px">
                    <img src="/storage/${previewImage}" alt="preview Image" class="img-fluid">
                    <figcaption class="d-flex align-items-center justify-content-center">
                        <h2 class="dish-action"><a href="/catalog/dish/${item['id']}" id="show-dish-btm" style="color:inherit;">${item['title']}</a></h2>
                    </figcaption>
                  </figure>
                   <div class="d-flex justify-content-between tm-text-gray">
                    <span class="tm-text-gray-light">${item['created_at']}</span>

                    <span>${item['price']}$</span>
                   </div>

                </div>`;
                // <div class="row mt-3 mb-0">
                //     //     <div class="col"><a href="dish/${item['id']}/edit" id="edit-btn" style="color:inherit;">Edit</a></div>
                //     //     <div class="col" id="delete-btn">Delete</div>
                //     // </div>
                // if ( item['id'] != user_id) {
                //     console.log(item['id']+'item id')
                //     console.log(user_id+'user id')
                //     $('#edit-btn, #delete-btn').remove();
                // }

                $('#catalog').append(row);
            });

            $('#edit-btn, #delete-btn, #show-dish-btm')
                .on('mouseenter', function () {
                    $(this).css("color", "#5c6772");
                })
                .on('mouseleave', function () {
                    $(this).css("color", "inherit");
                });

        }
        function initCatalog(data = {}) {
            $.ajax({
                url: '/catalog',
                method: 'get',
                dataType: 'json',
                data: data,
                success: function (data) {
                    console.log(data);
                    catalog(data);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error: ' + textStatus + ' - ' + errorThrown);
                }

            });
        }

        $(document).ready(function () {
            initCatalog();

            $('#filter-input-btn').on("click", function () {
                console.log('Filter input');

                let keyword = $('#keyword').val();
                let price = [$('#min-price').val(), $('#max-price').val()];

                console.log(keyword, price);
                let formData = new FormData();

                formData.append('keyword', keyword);
                formData.append('price', price);
                //
                for (var pair of formData.entries()) {
                    console.log(pair[0]+ ', ' + pair[1]);
                }

                $.ajax({
                    url: '/catalog/filter',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        $('#catalog').remove()
                        let row = `<div class="row tm-mb-90 tm-gallery" id="catalog">`
                        $('#main-catalog').append(row);
                        catalog(data)

                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert('Error: ' + textStatus + ' - ' + errorThrown);
                    }

                });


            });
        });
    </script>

    <style type="text/css">

        .filter-input {
            border: 1px solid;
            margin-left: 10px;
            width: 200px;
        }
        .filter-input-btn {
            color: white;
            background-color: #009999;
            margin-left: 10px;
            border: none;
            width: 100px;
            height: 50px;

        }


    </style>
@endsection
