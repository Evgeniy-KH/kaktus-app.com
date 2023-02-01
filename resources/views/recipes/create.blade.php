@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-12 mb-5">
                <div class="row mb-3" >
                    <h2 class="col-12 tm-text-primary">Your new Recipes</h2>
                </div>
                <div class="card mb-3 tm-bg-gray elevation-3" id="dish_card" data-id="{{ $user->id }}">
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
                            <label for="preview_image"
                                   class=" col-form-label tm-text-primary">{{ __('Add the preview image') }}</label>
                            <div id="input-group-preview_image">
                                <input id="preview_image" type="file"
                                       class="form-control rounded-0"
                                       name="preview_image" style="border: none">
                            </div>
                        </div>

                        <div class="row mb-3 ">
                            <label for="main_image"
                                   class=" col-form-label tm-text-primary">{{ __('Add the main image') }}</label>
                            <div id="input-group-main_image">
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

            $(document).ready(function () {
                $('#search-form').remove();
                console.log('Create new recipes')

                $('#create_dish_button').on("click", function () {
                    // $('.name').removeClass('is-invalid');
                    // $('.birthday').removeClass('is-invalid');
                    // $(".invalid-feedback").remove();

                    let id_user = $('#dish_card').attr('data-id');
                    let title = $('#title').val();
                    let description = $('#description').val();
                    let ingredients = $('#ingredients').val();
                    let price = $('#price').val();
                    let preview_image = $('#preview_image')[0].files[0];
                    let main_image = $('#main_image')[0].files[0];

                    console.log(id_user,title,description,ingredients,price,main_image,preview_image);


                    $.ajax({
                        url: `/api/personal/personal/${id}`,
                        type: 'PATCH',
                        data: {
                            _method: 'PATCH',
                            'name': name,
                            'birthday': birthday,
                        },
                        dataType: 'json',
                        success: function (data) {
                            if (data.id) {
                                let id = data.id
                                initFill(id);
                                alert('Personal info has beed updated successfully');
                            }
                            // location.reload();
                        },
                        error: function (data) {
                            if (data.status === 422) {
                                var errors = data.responseJSON.errors;
                                console.log(errors);
                                $.each(errors, function (key, value) {
                                    console.log(key);
                                    if (key === 'name') {
                                        $('#name').addClass('is-invalid');
                                        let rowError = `<div class="invalid-feedback"> ${value[0]} </div>`
                                        $('#input-group-name').append(rowError);
                                    } else if (key === 'birthday') {
                                        $('#dob').addClass('is-invalid');
                                        let rowError = `<div class="invalid-feedback"> ${value[0]} </div>`
                                        $('#input-group-dob').append(rowError);
                                    }
                                });
                            }
                        },
                    });
                });
            });
        </script>
@endsection
