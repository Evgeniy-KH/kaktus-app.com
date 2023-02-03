@extends('layouts.app')

@section('content')

    <div class="container-fluid tm-container-content tm-mt-60">
        <div class="row mb-4 dish-title" >
        </div>
        <div class="row tm-mb-90">
            <div class="col-xl-8 col-lg-7 col-md-6 col-sm-12 dish-image">
            </div>
            <div class="col-xl-4 col-lg-5 col-md-6 col-sm-12" style="height: 650px ;">
                <div class="tm-bg-gray tm-video-details">
                    <div class="mb-4 dish-description"
                    style="max-height: 150px; overflow-y: auto; /">
                    </div>
                    <p class="mb-4"><h4 class="tm-text-gray-dark">Ingredient</h4><div class="dish-ingredients tm-text-gray"></div></p>
                    <div class="mb-2 d-flex flex-wrap">
                        <div class="mr-4 mb-2">
                            <h4 class="dish-price"></h4>
                        </div>
                    </div>

                    <div class="mb-2 d-flex flex-wrap">
                        <div class="mr-4 mb-2">
                            <h4 class="dish-created-data"></h4>
                        </div>
                    </div>
                    <div class="dish-tags">
                        <h4 class="tm-text-gray-dark mb-3 dish-tag">Tags</h4>
                        <a href="#" class="tm-text-primary mr-4 mb-2 d-inline-block">Real Estate</a>
                    </div>
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

        let user_id = '{{Auth::user()->id}}';

        function initDish(data = {}) {
            $.ajax({
                url: '/catalog/dish/show/{{$id}}',
                method: 'get',
                dataType: 'json',
                data: data,
                success: function (data) {

                    data['created_at'] = new Date(data['created_at']).toLocaleDateString("en-US", {
                        day: 'numeric',
                        month: 'short',
                        year: 'numeric'
                    });

                    let title = `<h1 class="col-12 tm-text-primary">${data['title']}</h1>`;
                    let image =`<figure class="effect-ming tm-video-item main-image">
                    <img src="/storage/${data['get_dish_images'][1]['image']}" alt="Image" class="img-fluid" style="width: 1155px; height: 650px ;">
                    <figcaption class="d-flex align-items-center justify-content-center action">
                       <h2 class="dish-action justify-content-between">
                       <div class="row mt-3 mb-0 ">
<div class="col"><a href="dish/${data['id']}/edit" id="edit-btn" style="color:inherit;">Edit</a></div>
                       <div class="col" id="delete-btn">Delete</div></div></h2>
                    </figcaption>
                  </figure>`

                    let description =`<h4 class="tm-text-gray-dark mb-3">${data['description']}</h4>`;
                    $(".dish-ingredients").html(data['ingredients']);
                    let price = `<span class="tm-text-gray-dark">Price: </span><span class="tm-text-primary">${data['price']}$</span>`
                    let created = `<span class="tm-text-gray-dark">Published: </span><span class="tm-text-gray">${data['created_at']}$</span>`


                    $('.dish-title').append(title);
                    $('.dish-image').append(image);
                    $('.dish-description').append(description);
                    $('.dish-price').append(price);
                    $('.dish-created-data').append(created);

                    if (data['tags']) {
                        $.each(data['tags'], function (i, item) {
                            let tag = `<a href="#" class="tm-text-primary mr-4 mb-2 d-inline-block">${data['tags']}</a>`
                            $('.dish-tag').append(tag);
                        })
                    } else {
                        $('.dish-tags').remove()
                    }

                    if ( data['user_id'] != user_id) {
                            $('.main-image').remove();
                        let image =`<img src="/storage/${data['get_dish_images'][1]['image']}" alt="Image" class="img-fluid" style="width: 1155px; height: 650px ;">`
                        $('.dish-image').append(image);
                    }

                    $('#edit-btn, #delete-btn, #show-dish-btm')
                        .on('mouseenter', function() {
                            $(this).css("color", "#5c6772");
                        })
                        .on('mouseleave', function() {
                            $(this).css("color", "inherit");
                        });



                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error: ' + textStatus + ' - ' + errorThrown);
                }

            });
        }

        $(document).ready(function () {
            initDish();

        });
    </script>

    <style type="text/css">

        /*.dish-image {*/
        /*    height: 1155px !important;*/
        /*    width: 650px !important;*/
        /*}*/

    </style>
@endsection
