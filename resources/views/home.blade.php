@extends('layouts.app')

@section('content')
    <div class="container-fluid tm-container-content tm-mt-60 ">
        <div class="row mb-4">
            <form class="d-flex justify-content-center" id="filter-form">
                <input class="tm-search-input filter-input" type="text" id="min-price" placeholder="Min price"
                       aria-label="Search">
                <input class="tm-search-input filter-input" type="text" id="max-price" placeholder="Max price"
                       aria-label="Search">
                <input class="tm-search-input filter-input" type="text" id="keyword" placeholder="Keyword"
                       aria-label="Search">
                <select class="search_tag ml-4" id="select-tag" multiple="multiple" style="height: 50px;">
                </select>
                <input class="btn btn-primary filter-input-btn " id="filter-input-btn"
                       value="{{ __('Filter') }}">
            </form>
        </div>
        <div class="row mb-4">
            <h2 class="col-6 tm-text-primary">
                Dishes
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
                    previewImage = image['image'];
                } else if (image['type_id'] == '1') {
                    mainImage = image['image'];
                }
            })

            return {"previewImage": previewImage, "mainImage": mainImage};
        }

        function checkTags(tags) {
            let tagList = [];

            $.each(tags, function (i, tag) {
                let tagTitle = tag['title'];
                tagList.push(tagTitle);
            })

            return tagList;
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
            $('#catalog').remove();
            let row = `<div class="row tm-mb-90 tm-gallery" id="catalog">`;
            $('#main-catalog').prepend(row);

            $.each(data, function (i, item) {
                let countLikes = likes(item['likes_count']);
                let countLikesShow = 'hidden';

                if (countLikes != '') {
                    countLikesShow = ' hidden-avatars';
                }

                let classLikes = classLike(item['likes']);
                let likedUsersIds = getLikedUsersIds(item['likes']);
                let rows = '';

                if (likedUsersIds.length != 0) {
                    rows = likeUserData(likedUsersIds, item['id']);
                }

                item['created_at'] = new Date(item['created_at']).toLocaleDateString("en-US", {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                });

                let images = checkImages(item['dish_images']);// return of this function  variables
                let tagList = checkTags(item['tags']);// return of this function  variables\
                let arrayTags = [];

                $.each(tagList, function (i, tag) {
                    let tagRow = `<span class="tm-text-gray-light">${tag}</span>`;
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
                   <span id="favorite_${item['id']}" class="favourite"><i class="nav-icon far fa-heart"></i></span>
                    <div id="avatar-group-${item['id']}" class="avatar-group" >
                    <div class=${countLikesShow}>
                     ${countLikes}
                    </div> </div>
                    <span class="like-btn">
                        <i id="like_${item['id']}" class="${classLikes} fa-thumbs-up"></i></span>
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

        function likeUserData(data, dishID) {
            $.ajax({
                url: `/user/dish/users`,
                type: 'get',
                dataType: 'json',
                data: {
                    usersId: data
                },
                success: function (data) {
                    let AvatarRow = displayAvatars(data)
                    addAvatarsRow(AvatarRow, dishID);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error: ' + textStatus + ' - ' + errorThrown);
                },
            });
        }

        function addAvatarsRow(AvatarRow, dishID) {
            let element = $(document).find('#avatar-group-' + dishID);
            element.prepend(AvatarRow);
        }

        function displayAvatars(users) {
            let avatarsRows = "";

            $.each(users, function (i, user) {
                let avatarImage = 'https://cdn-icons-png.flaticon.com/512/37/37943.png?w=826&t=st=1677848744~exp=1677849344~hmac=e1d108013214838460e03f365e6a782c753b5d6f343be6c557c3ed78c0359eb1'//if there is no user avatar, so it will show an avatar of unknown user

                if (user['avatar_path']) {
                    avatarImage = '/storage/images/' + user['avatar_path'];
                }

                let avatarRow = `<div class="avatar"><img src="${avatarImage}"></div>`;
                avatarsRows = avatarsRows + avatarRow;
            })

            return avatarsRows;
        }

        function getLikedUsersIds(data) {
            let usersIds = [];

            if (data.length != 0) {
                $.each(data, function (i, like) {
                    usersIds.push(like['user_id']);
                })
            }

            return usersIds;
        }

        function likes(likes_count) {
            let countLikes = '';

            if (likes_count != 0) {
                countLikes = likes_count;
            }

            return countLikes;
        }

        function classLike(likes) {
            let classLikes = 'likeable far';
            let usersIds = getLikedUsersIds(likes);

            if ($.inArray(parseInt(user_id), usersIds) > -1) {
                classLikes = 'unlikeable fas';
            }

            return classLikes;
        }

        function initCatalog(filters = {}) {
             if (filters['tagsId']){
                 filters['tagsId']= filters['tagsId'].toString()
             }

            $.ajax({
                url: '/catalog',
                type: 'get',
                data: {
                    price: filters['price'],
                    keyword: filters['keyword'],
                    tagsId: filters['tagsId'],
                },
                success: function (data) {
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

        function getFilters() {
            let filters = {};

            if ($('#keyword').val()) {
                let keyword = $('#keyword').val().match(/[\wа-я]+/ig);
                filters['keyword'] = keyword;
            }

            if ($('#min-price').val() != '' || $('#max-price').val() != '') {
                let price = [$('#min-price').val(), $('#max-price').val()].toString();
                filters['price'] = price;
            }

            if ($('.search_tag option:selected').length > 0) {
                let tagsId = [];

                $('.search_tag option:selected').each(function (i) {
                    tagsId.push($(this).val());
                    $('.search_tag')[0].sumo.unSelectItem(i);
                });

                filters['tagsId'] = tagsId;
                console.log(filters);
            }

            return filters;
        }

        function getUrlParams() {
            let urlParams = new URLSearchParams(window.location.search);
            let filtersUrl = {};

            for (let [key, value] of urlParams) {
                if (key === 'keyword') {
                    value = value.match(/^[a-zA-Z]*$/);

                } else if (key === 'price') {
                    value = value.replace(/[^0-9.,]/g, '');
                    value = value.split(",");
                    value[0] = value[0].replace(/^0+/, '');
                    value[1] = value[1].replace(/^0+/, '');
                    value = value.join(',');
                }

                filtersUrl[key] = value;
            }

            $.each(filtersUrl, function (key, value) {
                if (value == "" || value == "," | value == null) {
                    delete filtersUrl[key];
                }
            });

            $.each(filtersUrl, function (key, value) {
                if (key === 'tagsId') {
                    value = parseInt(value);
                    // $('.search_tag')[0].sumo.selectItem(value);/// not working
                } else if (key === 'keyword') {
                    $('#keyword').val(value);
                    // $('.search_tag')[0].sumo.selectItem(value);/// not working
                } else if (key === 'price') {
                    let price = value.split(",");
                    $('#min-price').val(price[0]);
                    $('#max-price').val(price[1]);
                }
            })

            return filtersUrl;
        }

        function updateUrl(filters) {
            let urlFilter = "";
            let index = 1;
            let filterLength = Object.keys(filters).length;

            $.each(filters, function (key, value) {
                if (filterLength != index) {
                    value += '&';
                }

                urlFilter += key + "=" + value;
                index++;
            });

            let url = 'http://kaktus-app.com/home';
            window.history.replaceState(null, null, url + "?" + urlFilter);
        }

        function addToFavourites(dishId) {
            $.ajax({
                type: 'post',
                url: '/user/dish/favorite',
                data: {
                    'dish_id': dishId,
                },
                success: function () {
                },
                error: function (data) {
                    let errors = data.responseJSON.message;
                    alert('Error: ' + errors);
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
                },
                error: function (data) {
                    let errors = data.responseJSON.message;
                    alert('Error: ' + errors);
                }
            });
        }

        function favoriteDish(data = {}) {
            $.ajax({
                url: `/user/favorite/dish`,
                type: 'get',
                dataType: 'json',
                data: data,
                success: function (data) {
                    $.each(data, function (index, item) {
                        let element = $(document).find('#favorite_' + item['dish_id']);
                        element.removeClass('favourite').addClass('disfavouring')
                        element.find('i').removeClass('far').addClass('fas IM here');
                    })
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error: ' + textStatus + ' - ' + errorThrown);
                },
            });
        }

        $(document).ready(function () {
            let filterUrl = getUrlParams();
            initCatalog(filterUrl);
            showTags();
            mask();

            $(window).on('load', function () {
                favoriteDish();
            })

            $(document).on('click', '.likeable', function (event) {
                let dishId = $(this).attr('id').split('_')[1];

                $.ajax({
                    type: 'post',
                    url: '/user/dish/like',
                    data: {
                        'dish_id': dishId,
                        'likeable_type': 'Dish'
                    },
                    success: function (data) {
                        initCatalog();
                    },
                    error: function (data) {
                        let errors = data.responseJSON.message;
                        alert('Error: ' + errors);
                    }
                });
            })

            $(document).on('click', '.unlikeable', function (event) {
                let dishId = $(this).attr('id').split('_')[1];

                $.ajax({
                    url: '/user/dish/unlike',
                    type: 'delete',
                    dataType: 'json',
                    data: {
                        _method: 'delete',
                        'dish_id': dishId,
                        'likeable_type': 'Dish'
                    },
                    success: function (data) {
                        initCatalog();
                    },
                    error: function (data) {
                        let errors = data.responseJSON.message;
                        alert('Error: ' + errors);
                    }
                });
            })

            $('#filter-input-btn').on("click", function () {
                let filters = getFilters();

                updateUrl(filters);
                initCatalog(filters);
            });

            $(document).on('click', '.tm-paging-link', function (event) {
                event.preventDefault();

                let page = $(this).attr('href').split('page=')[1];
                let filters = getFilters();

                updateUrl(filters);

                let url = $(location).attr('href');
                window.history.replaceState(null, null, url + "page=" + page);

                fetch_user_data(filters, page);
            });

            $(document).on('click', '.favourite', function (event) {
                let dishId = $(this).attr('id').split('_')[1];

                addToFavourites(dishId);

                $(this).removeClass('favourite').addClass('disfavouring');
                $(this).find('i').removeClass('far').addClass('fas');
            });

            $(document).on('click', '.disfavouring', function (event) {
                let dishId = $(this).attr('id').split('_')[1];

                removeFromFavourites(dishId);

                $(this).removeClass('disfavouring').addClass('favourite');
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

        .avatar {
            width: 25px;
            height: 25px;
            overflow: hidden;
            border-radius: 50%;
            position: relative;
            background-color: #CCC;
            border: 1px solid #2c303a;
        }

        .avatar img {
            object-fit: cover;
            width: 100%;
            height: 100%;
        }

        .hidden-avatars {
            width: 25px;;
            height: 25px;;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-left: 3px;
            margin-right: 23px;
            background-color: #CCC;
            color: #fff;
        }

        .avatar-group {
            display: flex;
            margin-right: -60px;
        }

        .avatar-group.rtl {
            direction: rtl;
        }

        .avatar:hover:not(:last-of-type) {
            transform: translate(10px);
        }

        .avatar {
            margin-left: -20px;
            transition: transform 0.3s ease;
            cursor: pointer;
        }

        .avatar :hover:not(:last-of-type) {
            transform: translate(-10px);
        }
    </style>
@endsection
