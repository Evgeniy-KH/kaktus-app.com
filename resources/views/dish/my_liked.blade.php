@extends('layouts.app')

@section('content')
    <div class="container-fluid tm-container-content tm-mt-60 ">
        <div class="row mb-4">
            <h2 class="col-6 tm-text-primary">
                My liked dishes
            </h2>
        </div>
        <div id="main-catalog">
            <div class="row tm-mb-90 tm-gallery">
                <div class="table-responsive">
                    <table class="table dishes table-striped table align-middle ">
                        <thead>
                        <tr>
                            <th scope="col">Number</th>
                            <th scope="col">Image</th>
                            <th scope="col">Title</th>
                            <th scope="col">Price</th>
                            <th scope="col">Tags</th>
                        </tr>
                        </thead>
                        <tbody id="tbody">
                        </tbody>
                    </table>
                </div>
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

        function initCatalog() {
            $.ajax({
                url: '/user/dish/liked',
                type: 'post',
                data: {
                    "id": user_id
                },
                success: function (data) {
                    list(data['data'])
                },
                error: function (data) {
                    var errors = data.responseJSON.message;
                    alert('Error: ' + errors);
                }
            });
        }

        function list(data) {
            let numberDish = 1
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

                let row = `<tr>
               <th scope="row" data-id=${item['id']}">${numberDish}</th>
                 <td><figure class="effect-ming tm-video-item" style="height: 50px; width: 75px">
                    <img src="/storage/${previewImage}" alt="preview Image" class="img-fluid">
                  </figure></td>
                 <td> <h5 class="dish-action"><a href="/catalog/dish/${item['id']}" id="show-dish-btm" style="color:inherit;">${item['title']}</a></h5></td>
                <td>${item['price']}$</td>
                 <td>${rowTags}</td>
              </tr>`

                $('#tbody').append(row);

                numberDish += 1
            })
        }

        $(document).ready(function () {
            initCatalog();
        })

    </script>

    <style type="text/css">

        .table {
            border: 1px solid;
            width: 800px;
        }

    </style>
@endsection
