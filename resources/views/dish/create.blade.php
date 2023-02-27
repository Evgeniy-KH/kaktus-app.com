@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-12 mb-5">
                <div class="row mb-3" >
                    <h2 class="col-12 tm-text-primary">Your new Recipes</h2>
                </div>
                <div class="card mb-3 tm-bg-gray elevation-3" id="dish_card">
                    <div class="tm-video-details">

                        <div class="row mb-3">
                            <label for="title"
                                   class=" col-form-label text-md-end tm-text-primary">{{ __('Dish title') }}</label>
                            <div id="input-group-title">
                                <input id="title" type="text"
                                       class="form-control rounded-0" name="title"
                                       value="{{ old('title') }}" required autocomplete="Recipe title" autofocus>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="description"
                                   class=" col-form-label  tm-text-primary">{{ __('Description') }}</label>
                            <div id="input-group-description">
                        <textarea id="description" class="form-control rounded-0"
                                  name="description" required autocomplete="description"> {{ old('description') }} </textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="ingredients"
                                   class=" col-form-label  tm-text-primary">{{ __('Ingredients') }}</label>
                            <div id="input-group-ingredients">
                        <textarea id="ingredients" class="form-control rounded-0"
                                  name="ingredients" required autocomplete="ingredients"> {{ old('ingredients') }} </textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="price"
                                   class=" col-form-label  tm-text-primary">{{ __('Price') }}</label>
                            <div id="input-group-price">
                                <input type="number" min="0.00" max="10000.00" step="0.01"  name="price" id="price"/>
                            </div>
                        </div>

                        <div class="row mb-3">

                            <label for="tags"
                                   class=" col-form-label  tm-text-primary">{{ __('Tags') }}</label>
                            <div id="input-group-tags">
                                <select name="tag_ids[]" class="form-control rounded-0 select-tags" multiple="multiple"
                                        data-placeholder="Select a Tag">

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
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary" id="create_dish_button"
                                        style="background-color:#009999; border: none">
                                    {{ __('Create new recipes') }}
                                </button>
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

            function showTags (data ={}) {
                $.ajax({
                    url: `/catalog/dish/getTags`,
                    type: 'get',
                    dataType: 'json',
                    data: data,
                    success: function (data) {
                        $.each(data, function (i, item) {
                        let tags = `<option
                            {{
                            is_array( old('tag_ids')) && in_array(item['id'], old('tag_ids')) ? ' selected' : ''}} value="${item['id']}"
                                                             >${item['title']}</option>`
                            $('.select-tags').append(tags);
                        });
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert('Error: ' + textStatus + ' - ' + errorThrown);
                    },
                });

            }
            $(document).ready(function () {
                $('#search-form').remove();
                showTags ();
                $('#create_dish_button').on("click", function () {

                   //  let id_user = $('#dish_card').attr('data-id');
                   //  let title = $('#title').val();
                   //  let description = $('#description').val();
                   //  let ingredients = $('#ingredients').val();
                   //  let price = $('#price').val();
                   //  let preview_image = $('#preview_image')[0].files[0];
                   //  let main_image = $('#main_image')[0].files[0];
                   // // let tags_ids = $('#tags').val();

                    var formData = new FormData();
                    //  let description = $('#description').val();
                    //  let ingredients = $('#ingredients').val();

                    formData.append("user_id", $('#user-edit').attr('data-id'));
                    formData.append("title", $('#title').val());
                    formData.append("description", $('#description').val());
                    formData.append("ingredients", $('#ingredients').val());
                    formData.append("price", $('#price').val());
                    formData.append("preview_image", $('#preview_image')[0].files[0]);
                    formData.append("main_image", $('#main_image')[0].files[0]);


                    $.ajax({
                        url: `/user/dish/store`,
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (data) {
                                alert('New dish has been create successfully');
                                location.reload();
                        },
                        error: function (data) {
                            if (data.status === 422) {
                                var errors = data.responseJSON.errors;
                                console.log(errors);
                                $.each(errors, function (key, value) {
                                    console.log(key);
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
                        },
                    });
                });
            });
        </script>
@endsection
