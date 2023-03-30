@extends('layouts.app')

@section('content')

    <div class="container-fluid tm-container-content tm-mt-60">
        <div class="row mb-4 dish-title">
        </div>
        <div class="row tm-mb-90">
            <div class="col-xl-8 col-lg-7 col-md-6 col-sm-12 dish-image">
            </div>
            <div class="col-xl-4 col-lg-5 col-md-6 col-sm-12" style="height: 650px ;">
                <div class="tm-bg-gray tm-video-details">
                    <div class="mb-4 dish-description"
                         style="max-height: 250px; overflow-y: auto; /">
                    </div>
                    <p class="mb-4"><h4 class="tm-text-gray-dark">Ingredient</h4>
                    <div class="dish-ingredients tm-text-gray"></div>
                    </p>
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

        let userId = $('#user-edit').attr('data-id');
        let id = {{$id}};

        function checkImages(images) {
            let previewImage = '';
            let mainImage = '';

            $.each(images, function (i, image) {
                if (image['type_id'] == '0') {/// delete preview image or not ???
                    previewImage = image['path']
                } else if (image['type_id'] == '1') {
                    mainImage = image['path']
                }
            })

            return {"previewImage": previewImage, "mainImage": mainImage}
        }

        function initDish(data = {}) {
            $.ajax({
                url: '/catalog/dish/show/{{$id}}',
                method: 'get',
                dataType: 'json',
                data: data,
                success: function (data) {
                    data = data['data'];
                    data['created_at'] = new Date(data['created_at']).toLocaleDateString("en-US", {
                        day: 'numeric',
                        month: 'short',
                        year: 'numeric'
                    });

                    let images = checkImages(data['dish_images']);// return of this function let variables
                    let mainImage = images["mainImage"];

                    let title = `<h1 class="col-12 tm-text-primary">${data['title']}</h1>`;
                    let image = `<figure class="effect-ming tm-video-item main-image">
                    <img src="/storage/${mainImage}" alt="Image" class="img-fluid" style="width: 1155px; height: 650px ;">
                    <figcaption class="d-flex align-items-center justify-content-center action">
                       <h2 class="dish-action justify-content-between">
                       <div class="row mt-3 mb-0" style="color:inherit; font-size: 5rem">
                        <div class="col mr-4" id="edit-btn"><a href="/user/dish/${data['id']}/edit" style="color:inherit;">Edit</a></div>
                       <div class="col mr-4" id="delete-btn" data-id="${id}">Delete</div></div></h2>
                    </figcaption>
                  </figure>`
                    let description = `<h4 class="tm-text-gray-dark mb-3">${data['description']}</h4>`;
                    let price = `<span class="tm-text-gray-dark">Price: </span><span class="tm-text-primary">${data['price']}$</span>`
                    let created = `<span class="tm-text-gray-dark">Published: </span><span class="tm-text-gray">${data['created_at']}$</span>`

                    $('.dish-title').append(title);
                    $('.dish-image').append(image);
                    $('.dish-description').append(description);
                    $(".dish-ingredients").html(data['ingredients']);
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

                    if (data['user_id'] != userId) {
                        $('.main-image').remove();
                        let image = `<img src="/storage/${data['dish_images'][1]['image']}" alt="Image" class="img-fluid" style="width: 1155px; height: 650px ;">`
                        $('.dish-image').append(image);
                    }

                    $('#edit-btn, #delete-btn, #show-dish-btm')
                        .on('mouseenter', function () {
                            $(this).css("color", "#5c6772");
                        })
                        .on('mouseleave', function () {
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

            $(document).on("click", "#delete-btn", function () {
                $.ajax({
                    url: `/user/dish/` + id,
                    type: 'delete',
                    data: {_method: 'delete'},
                    dataType: 'json',
                    success: function (data) {
                        window.location.href = "/home"
                    },
                });
            });
        });
    </script>

    <style type="text/css">
    </style>
@endsection
