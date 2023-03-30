@extends('layouts.app')

@section('content')
    <div class="container-fluid tm-container-content tm-mt-60 ">
        <div class="row mb-4">
            <h2 class="col-6 tm-text-primary">
                My Dishes
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

        let userId = $('#user-edit').attr('data-id');

        function checkImages(images) {
            let previewImage = '';
            let mainImage = '';

            $.each(images, function (i, image) {
                if (image['type_id'] == '0') {
                    previewImage = image['path']
                } else if (image['type_id'] == '1') {
                    mainImage = image['path']
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

        function catalog(data) {

            $('#catalog').remove()
            let row = `<div class="row tm-mb-90 tm-gallery" id="catalog">`
            $('#main-catalog').prepend(row);

            $.each(data['data'], function (i, item) {
                let countLikes = likes(item['likes_count'])
                let countLikesShow = 'hidden'

                if (countLikes != '') {
                    console.log('countLikes')
                    countLikesShow = ' hidden-avatars'
                }

                let classLikes = classLike(item['likes'])
                let likedUsersIds = getLikedUsersIds(item['likes'])
                let rows = ''

                if (likedUsersIds.length != 0) {
                    rows = likeUserData(likedUsersIds, item['id'])
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
                    <span class="tm-text-gray-light" id="time">${item['created_at']}
                     ${rowTags}</span>
                   <span id="favorite_${item['id']}" class="favourite"><i class="nav-icon far fa-heart"></i></span>

                       <span class="like-btn">
                        <i id="like_${item['id']}" class="${classLikes} fa-thumbs-up"></i></span>
                  <div id="avatar-group-${item['id']}" class="avatar-group" >
                    <div class=${countLikesShow}>
                    ${countLikes}
                   </div>
                   </div>
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
            $.ajax({
                url: '/catalog',
                type: 'get',
                data: {
                    userId:userId,
                },
                success: function (data) {
                    catalog(data['data']);
                    pagination(data['data']);
                },
                error: function (data) {
                    let errors = data.responseJSON.message;
                    alert('Error: ' + errors);
                }
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
                    addAvatarsRow(AvatarRow, dishID)
                },
                error: function (jqXHR, textStatus, errorThrown) {
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
                let avatarImage = 'https://cdn-icons-png.flaticon.com/512/37/37943.png?w=826&t=st=1677848744~exp=1677849344~hmac=e1d108013214838460e03f365e6a782c753b5d6f343be6c557c3ed78c0359eb1'
                if (user['avatar_path']) {
                    avatarImage = '/storage/images/' + user['avatar_path']
                }
                let avatarRow = `<div class="avatar"><img src="${avatarImage}"></div>`
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
                countLikes = likes_count
            }

            return countLikes;
        }

        function classLike(likes) {
            let classLikes = 'likeable far';
            let usersIds = getLikedUsersIds(likes)

            if ($.inArray(parseInt(userId), usersIds) > -1) {
                classLikes = 'unlikeable fas';
            }

            return classLikes;
        }

        function pagination(data = {}) {
            console.log(data);
            $('.tm-paging-link').remove();

            $.each(data['links'], function (i, item) {
                if (i === 0) {
                    let row = `<a class="btn btn-primary mb-2 tm-paging-link filter-input-btn"  href="${item['url']}" >Previous</a>`
                    $('.pagination').prepend(row);
                } else if (i === data['links'].length - 1) {
                    let row = `<a class="btn btn-primary tm-paging-link filter-input-btn " href="${item['url']}">Next Page</a>`
                    $('.pagination').append(row);
                } else {
                    let active = item['active'] ? 'active' : '';
                    let row = `<a  class="tm-paging-link ${active}"  href="${item['url']}" >${item['label']}</a>`;
                    $('.pagination-center').append(row);
                }
            });
        }


        $(document).ready(function () {
            initCatalog();

            $(document).on('click', '.tm-paging-link', function (event) {
                event.preventDefault();

                let page = $(this).attr('href').split('page=')[1];

                let url = $(location).attr('href')
                window.history.replaceState(null, null, url + "page=" + page);

                fetch_user_data(page);
            });

            function fetch_user_data( page) {
                $.ajax({
                    url: "/catalog?page=" + page,
                    data: {
                        userId:userId,
                    },
                    success: function (data) {
                        catalog(data['data']);
                        pagination(data['data']);
                    }
                });
            }
        })

    </script>

    <style type="text/css">

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
            margin-left: -15px;
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
