@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-12 mb-5">
                <div class="row mb-3" >
                    <h2 class="col-12 tm-text-primary ">Your new Recipes</h2>
                </div>
                <div class="card mb-3 tm-bg-gray elevation-3" id="edit-card">
                    <div class="tm-video-details">

                        <div class="row mb-3">
                            <label for="recipe_title"
                                   class=" col-form-label text-md-end tm-text-primary">{{ __('Recipe title') }}</label>
                            <div id="input-group-recipe_title">
                                <input id="title" type="text"
                                       class="form-control rounded-0" name="recipe_title"
                                       value="{{ old('recipe_title') }}" required autocomplete="Recipe title" autofocus>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="direction"
                                   class=" col-form-label  tm-text-primary">{{ __('Direction') }}</label>
                            <div id="input-group-direction">
                        <textarea id="direction" class="form-control rounded-0"
                                  name="direction" required autocomplete="direction"> {{ old('direction') }} </textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="preview_image"
                                   class=" col-form-label tm-text-primary">{{ __('Add preview_image') }}</label>
                            <div id="input-group-preview_image">
                                <input id="preview_image" type="file"
                                       class="form-control rounded-0"
                                       name="preview_image" style="border: none">
                            </div>
                        </div>

                        <div class="row mb-3 ">
                            <label for="main_image"
                                   class=" col-form-label tm-text-primary">{{ __('Add main_image') }}</label>
                            <div id="input-group-main_image">
                                <input id="main_image" type="file"
                                       class="form-control rounded-0"
                                       name="main_image" style="border: none">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary"
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
            });
        </script>
@endsection
