@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-12 mb-5">
                <div class="row mb-3">
                    <h2 class="col-12 tm-text-primary">Edit your Recipes</h2>
                </div>
                <div class="card mb-3 tm-bg-gray elevation-3" id="dish_card" data-id="{{ $id }}">
                    <div class="tm-video-details">
                        <div class="row mb-3">
                            <label for="title"
                                   class=" col-form-label text-md-end tm-text-primary">{{ __('Dish title') }}</label>
                            <div id="input-group-title">
                                {{--                                <input id="title" type="text"--}}
                                {{--                                       class="form-control rounded-0" name="title"--}}
                                {{--                                       value="{{ old('title') }}" required autocomplete="Recipe title" autofocus>--}}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="description"
                                   class=" col-form-label  tm-text-primary">{{ __('Description') }}</label>
                            <div id="input-group-description">
                                {{--                        <textarea id="description" class="form-control rounded-0"--}}
                                {{--                                  name="description" required autocomplete="description"> {{ old('description') }} </textarea>--}}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="ingredients"
                                   class=" col-form-label  tm-text-primary">{{ __('Ingredients') }}</label>
                            <div id="input-group-ingredients">
                                {{--                        <textarea id="ingredients" class="form-control rounded-0"--}}
                                {{--                                  name="ingredients" required autocomplete="ingredients"> {{ old('ingredients') }} </textarea>--}}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="price"
                                   class=" col-form-label  tm-text-primary">{{ __('Price') }}</label>
                            <div id="input-group-price">
                                {{--                                <input type="number" min="0.00" max="10000.00" step="0.01"  name="price" id="price"/>--}}
                            </div>
                        </div>

                        <div class="row mb-3">

                            <label for="tags"
                                   class=" col-form-label  tm-text-primary">{{ __('Tags') }}</label>
                            <div>
                                <select name="tag_ids[]" class="select2 select-tags" multiple="multiple"
                                        data-placeholder="Select a Tag" style="width: 100%;" id="input-group-tags">
                                    {{--                                        <option--}}
                                    {{--                                            {{ is_array( old('tag_ids')) && in_array($tag->id, old('tag_ids')) ? ' selected' : ''}} value="{{ $tag->id }}"--}}
                                    {{--                                        >{{ $tag->title }}</option>--}}
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="preview_image"
                                   class=" col-form-label tm-text-primary">{{ __('Add the preview image') }}</label>
                            <div id="input-group-preview-image">
                                <input id="preview_image" type="file"
                                       class="form-control rounded-0"
                                       name="preview_image" style="border: none">
                            </div>
                        </div>

                        <div class="row mb-3 ">
                            <label for="main_image"
                                   class=" col-form-label tm-text-primary">{{ __('Add the main image') }}</label>
                            <div id="input-group-main-image">
                                <input id="main_image" type="file"
                                       class="form-control rounded-0"
                                       name="main_image" style="border: none">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary" id="update_dish_button"
                                        style="background-color:#009999; border: none">
                                    {{ __('Update your dish') }}
                                </button>
                            </div>
                            <div class="col-md-6 ">
                                <button type="submit" class="btn btn-primary" id="delete_dish_button"
                                        style="background-color:#009999; border: none">
                                    {{ __('Delete your dish') }}
                                </button>
                            </div>
                        </div>

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

        let user_id = $('#user-edit').attr('data-id');

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
            console.log(mainImage)

            return {"previewImage": previewImage, "mainImage": mainImage}
        }
        function yourDish(data = {}) {
            $.ajax({
                url: '/user/dish/{{$id}}/editData',
                method: 'get',
                dataType: 'json',
                data: data,
                success: function (data) {
                    data = data['data'];
                    showTags(data['tags']);
                    let images = checkImages(data['dish_images']);
                    console.log(images);
                    let title = `<input id="title" type="text"
                                       class="form-control rounded-0" name="title"
                                       value="${data['title']}" required autocomplete="Recipe title" autofocus>`
                    let ingredients = `<textarea id="ingredients" class="form-control rounded-0"
                                  name="ingredients" required autocomplete="ingredients">${data['ingredients']}</textarea>`
                    let description = `<textarea id="description" class="form-control rounded-0"
                                  name="description" required autocomplete="description">${data['description']}</textarea>`
                    let price = ` <input type="number" min="0.00" max="10000.00" step="0.01"  name="price"  value="${data['price']}" id="price"/>`

                    let preview_image = `<img src="/storage/${images["previewImage"]}" width="200" class="img-fluid img-thumbnail">`
                    let main_image = `<img src="/storage/${images["mainImage"]}" width="200" class="img-fluid img-thumbnail">`

                    $('#input-group-title').append(title);
                    $('#input-group-ingredients').append(ingredients);
                    $('#input-group-description').append(description);
                    $('#input-group-price').append(price);
                    $('#input-group-preview-image').prepend(preview_image);
                    $('#input-group-main-image').prepend(main_image);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error: ' + textStatus + ' - ' + errorThrown);
                }
            });
        }

        function showTags(data = {}) {
            let tagsArray = [];

            if (data.length != 0) {
                let tagsArray = data[0]['title'];
            }

            $.ajax({
                url: `/catalog/dish/getTags`,
                type: 'get',
                dataType: 'json',
                data: data,
                success: function (data) {
                    console.log(data);
                    $.each(data['data'], function (i, item) {
                        if (tagsArray.indexOf(item['title']) !== -1) {
                            $('.select-tags').append($('<option>', {
                                value: item['id'],
                                text: item['title'],
                                selected: true
                            }));
                        } else {
                            let tags = `<option
                            {{
                            is_array( old('tag_ids')) && in_array(item['id'], old('tag_ids')) ? ' selected' : ''}} value="${item['id']}"
                                                             >${item['title']}</option>`
                            $('.select-tags').append(tags);
                        }

                    });
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error: ' + textStatus + ' - ' + errorThrown);
                },
            });

        }

        $(document).ready(function () {
            yourDish();
            let dish_id = $('#dish_card').attr('data-id');

            $('#update_dish_button').on("click", function () {
                let formData = new FormData();

                formData.append(' _method', 'PATCH');
                formData.append("user_id", user_id);
                formData.append("id", $('#dish_card').attr('data-id'));
                formData.append("title", $('#title').val().replace(/[^a-zA-Za-åa-ö-w-я 0-9/@%!"#?¨'_.,]+/g, ""));
                formData.append("description", $('#description').val().replace(/[^a-zA-Za-åa-ö-w-я 0-9/@%!"#?¨'_.,] +/g, ""));
                formData.append("ingredients", $('#ingredients').val().replace(/[^a-zA-Za-åa-ö-w-я 0-9/@%!"#?¨'_.,]+/g, ""));
                formData.append("price", $('#price').val());

                if ($('#preview_image')[0].files[0]) {
                    let preview_image = $('#preview_image')[0].files[0];
                    formData.append("preview_image", preview_image);
                }

                if ($('#main_image')[0].files[0]) {
                    let main_image = $('#main_image')[0].files[0];
                    formData.append("main_image", main_image);
                }

                let tags = $('.select-tags').val();
                for (let i = 0; i < tags.length; i++) {
                    formData.append('tag_ids[]', tags[i]);
                }
                // for (let [key, value] of formData) {
                //     console.log(`${key}: ${value}`)
                // }
                $.ajax({
                    url: `/user/dish/{{$id}}`,
                    type: 'post',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        alert('New dish has been create successfully');
                      ///  window.location.href = "/home";
                    },
                    error: function (data) {
                        if (data.status === 422) {
                            let errors = data.responseJSON.errors;
                            $.each(errors, function (key, value) {
                                if (key === 'title') {
                                    $('#title').addClass('is-invalid');
                                    let rowError = `<div class="invalid-feedback"> ${value[0]} </div>`
                                    $('#input-group-title').append(rowError);
                                } else if (key === 'description') {
                                    $('#description').addClass('is-invalid');
                                    let rowError = `<div class="invalid-feedback"> ${value[0]} </div>`
                                    $('#input-group-description').append(rowError);
                                } else if (key === 'ingredients') {
                                    $('#ingredients').addClass('is-invalid');
                                    let rowError = `<div class="invalid-feedback"> ${value[0]} </div>`
                                    $('#input-group-ingredients').append(rowError);
                                } else if (key === 'price') {
                                    $('#price').addClass('is-invalid');
                                    let rowError = `<div class="invalid-feedback"> ${value[0]} </div>`
                                    $('#input-group-price').append(rowError);
                                } else if (key === 'preview_image') {
                                    $('#preview_image').addClass('is-invalid');
                                    let rowError = `<div class="invalid-feedback"> ${value[0]} </div>`
                                    $('#input-group-preview-image').append(rowError);
                                } else if (key === 'main_image') {
                                    $('#main_image').addClass('is-invalid');
                                    let rowError = `<div class="invalid-feedback"> ${value[0]} </div>`
                                    $('#input-group-main-image').append(rowError);
                                }
                            });
                        }

                        if (data.status === 500) {
                            let errors = data.responseJSON.data;
                            alert(errors['message']);
                        }
                    },
                });
            });
        });
    </script>

@endsection
