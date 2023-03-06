@extends('layouts.app')

@section('content')
    <div class="container-fluid tm-container-content tm-mt-60 ">
        <div class="row mb-4">
            <h2 class="col-6 tm-text-primary">
               My Favorites Dishes
            </h2>
        </div>
        <div id="main-catalog">
            <div class="row tm-mb-90 tm-gallery" id="catalog">
            </div> <!-- row -->
        </div>
        <div class="row align-items-end">
            <div class="col-12 d-flex justify-content-between align-items-center tm-paging-col pagination a">
                <div class="tm-paging d-flex pagination-center">
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


        $('.search_tag').SumoSelect({
            placeholder: 'Tags',
            csvDispCount: 3,
            search: true,
        });

        $(".search_tag").css("margin-left", "10px");

        function mask() {
            $('#min-price').mask("#.##0.00", {reverse: true});
            $('#max-price').mask("#.##0.00", {reverse: true});
        }

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

        function checkTags(tags) {
            let tagList = [];

            $.each(tags, function (i, tag) {
                let tag_title = tag['title'];
                tagList.push(tag_title);
            })

            return tagList
        }

        function showTags(data = {}) {
            $.ajax({
                url: `/catalog/dish/getTags`,
                type: 'get',
                dataType: 'json',
                data: data,
                success: function (data) {
                    $.each(data, function (index, item) {
                        $('.search_tag')
                            .append($("<option></option>")
                                .attr("value", item['id'])
                                .attr("id", item['id'])
                                .text(item['title']));
                    });
                    $('.search_tag').SumoSelect().sumo.reload();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error: ' + textStatus + ' - ' + errorThrown);
                },
            });
        }

        function catalog(data) {
            $('#catalog').remove()
            let row = `<div class="row tm-mb-90 tm-gallery" id="catalog">`
            $('#main-catalog').prepend(row);

            $.each(data, function (i, item) {

                item['created_at'] = new Date(item['created_at']).toLocaleDateString("en-US", {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                });

                let images = checkImages(item['dish_images']);// return of this function  variables
                let tagList = checkTags(item['tags']);// return of this function  variables\
                let arrayTags = [];

                $.each(tagList, function (i, tag) {
                    let tagRow = `<span class="tm-text-gray-light">${tag}</span>`
                    arrayTags.push(tagRow);
                })

                let rowTags = arrayTags.join(",");
                let previewImage = images["previewImage"];
                let row = `<div class="catalog-item col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 mb-5">
                   <div class="hidden dish-author-id" data-id="${item['user_id']}"></div>
                    <div class="hidden dish-id" data-id=${item['id']}"></div>
                   <figure class="effect-ming tm-video-item" style="height: 209px; width: 372px">
                    <img src="/storage/${previewImage}" alt="preview Image" class="img-fluid">
                    <figcaption class="d-flex align-items-center justify-content-center">
                        <h2 class="dish-action"><a href="/catalog/dish/${item['id']}" id="show-dish-btm" style="color:inherit;">${item['title']}</a></h2>
                    </figcaption>
                  </figure>
                   <div class="d-flex justify-content-between tm-text-gray">
                    <span class="tm-text-gray-light" id="time">${item['created_at']}</span>
                    <span class="tm-text-gray-light"> ${rowTags}</span>
                   <span id="favorite_${item['id']}" class="disfavouring"><i class="nav-icon fas fa-heart"></i></span>
                   <span id="price" >${item['price']}$</span>
                   </div>
                </div>`;

                $('#catalog').prepend(row);

                $('#edit-btn, #delete-btn, #show-dish-btm')
                    .on('mouseenter', function () {
                        $(this).css("color", "#5c6772");
                    })
                    .on('mouseleave', function () {
                        $(this).css("color", "inherit");
                    });
            });
        }

        function initCatalog(filters = {}) {
            console.log(filters);
            $.ajax({
                url: '/user/favorite/dishes',
                type: 'get',
                data: {
                    price: filters['price'],
                    keyword: filters['keyword'],
                    tagsId: filters['tagsId'],
                },
                success: function (data) {
                    console.log(data);
                    catalog(data['data']);
                    pagination(data['links']);
                },
                error: function (data) {
                    var errors = data.responseJSON.message;
                    alert('Error: ' + errors);
                    // $(':input', '#filter-form')
                    //     .not(':button, :submit, :reset, :hidden')
                    //     .val('')
                    //     .prop('checked', false)
                    //     .prop('selected', false);
                }
            });
        }

        function pagination(data = {}) {
            $('.tm-paging-link').remove();

            $.each(data, function (i, item) {
                if (i === 0) {
                    let row = `<a class="btn btn-primary mb-2 tm-paging-link filter-input-btn"  href="${item['url']}" >Previous</a>`
                    $('.pagination').prepend(row);
                } else if (i === data.length - 1) {
                    let row = `<a class="btn btn-primary tm-paging-link filter-input-btn " href="${item['url']}">Next Page</a>`
                    $('.pagination').append(row);
                } else {
                    let active = item['active'] ? 'active' : '';
                    let row = `<a  class="tm-paging-link ${active}"  href="${item['url']}" >${item['label']}</a>`;
                    $('.pagination-center').append(row);
                }
            });
        }

        function removeFromFavourites(dishId) {

            $.ajax({
                type: 'post',
                url: '/user/dish/disfavouring',
                data: {
                    'dish_id': dishId,
                },
                success: function () {
                    initCatalog();
                },
                error: function (data) {
                    let errors = data.responseJSON.message;
                    alert('Error: ' + errors);
                }
            });
        }

        $(document).ready(function () {
            initCatalog();
            showTags();
            mask();

            $(window).on('load', function () {
                favoriteDish()
            })

            $(document).on('click', '.tm-paging-link', function (event) {
                event.preventDefault();

                let page = $(this).attr('href').split('page=')[1];
                let filters = getFilters()

                updateUrl(filters);
                let url = $(location).attr('href')
                window.history.replaceState(null, null, url + "page=" + page);

                fetch_user_data(filters, page);
            });

            $(document).on('click', '.favourite', function (event) {
                let dishId = $(this).attr('id').split('_')[1];
                addToFavourites(dishId)

                $(this).removeClass('favourite').addClass('disfavouring')
                $(this).find('i').removeClass('far').addClass('fas');
            });

            $(document).on('click', '.disfavouring', function (event) {
                let dishId = $(this).attr('id').split('_')[1];
                removeFromFavourites(dishId)

                $(this).removeClass('disfavouring').addClass('favourite')
                $(this).find('i').removeClass('fas').addClass('far');
            });

            function fetch_user_data(filters, page) {
                $.ajax({
                    url: "/catalog?page=" + page,
                    data: {
                        price: filters['price'],
                        keyword: filters['keyword'],
                        tagsId: filters['tagsId'],
                    },
                    success: function (data) {
                        catalog(data['data']);
                        pagination(data['links']);
                    }
                });
            }
        })

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

        .select-tags {
            background: none !important;
        }

        .SumoSelect > .CaptionCont > span.placeholder {
            color: #009999;
            font-family: inherit;
            font-size: inherit;
            line-height: inherit;
            text-align: start;
            cursor: text;
            padding: 5px 15px;

        }

        .SumoSelect > .CaptionCont {
            border: 1px solid #009999;
        }
    </style>
@endsection
