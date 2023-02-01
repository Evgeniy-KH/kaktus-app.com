@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-4 col-12 mb-5">
                <div class="card-body" id="edit-card" data-id="{{ $id }}">

                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="tm-text-primary mt-2" style="text-align: center; font-weight: bold;">Edit
                                Personal Info</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name"
                                       class=" col-form-label text-md-end tm-text-primary"> {{ __('Name') }}</label>
                                <div id="input-group-name">
                                    <input id="name" type="text"
                                           class="form-control rounded-0"
                                           name="name"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="birthday_at"
                                       class=" col-form-label text-md-end tm-text-primary">{{ __('Birthday') }}</label>
                                <div d="input-group-birthday">
                                    <div class="input-group date" id="datepicker">
                                        <input type="text" class="form-control" name="birthday" id="birthday">
                                        <span class="input-group-append">
                                            </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-6">
                                    <button type="submit" class="btn btn-primary" id="personal-info-update"
                                            style="background-color:#009999; border: none; font-size: 15px ; font-size: 15px">
                                        {{ __('Save changes') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="tm-text-primary mt-2" style="text-align: center; font-weight: bold;">Edit
                                password</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <div>
                                    <label for="current_password"
                                           class=" col-form-label  tm-text-primary">{{ __('Old Password') }}</label>
                                    <div id="input-group-current_password">
                                        <input id="current_password" type="password"
                                               class="form-control rounded-0"
                                               name="password" required autocomplete="current_password">
                                    </div>
                                </div>
                                <label for="password"
                                       class=" col-form-label  tm-text-primary">{{ __('New Password') }}</label>
                                <div id="input-group-password">
                                    <input id="password" type="password"
                                           class="form-control rounded-0"
                                           name="password" required autocomplete="new-password">
                                </div>

                                <label for="password-confirm"
                                       class="col-form-label tm-text-primary">{{ __('Confirm New Password') }}</label>
                                <div id="input-group-password-confirm">
                                    <input id="password-confirm" type="password" class="form-control rounded-0"
                                           name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>
                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-6">
                                    <button type="submit" class="btn btn-primary" id="btn-update-password"
                                            style="background-color:#009999; border: none; font-size: 15px ">
                                        {{ __('Update password') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="tm-text-primary mt-2" style="text-align: center; font-weight: bold;">Edit
                                profile photo</h3>
                        </div>
                        <div class="card-body">
                            <div class="w-75 mb-2" id="preview-photo">
                                {{--                                <img src="{{ asset('/storage/images/'.Auth::user()->image)}}" alt="preview_image"--}}
                                {{--                                     class="w-50">--}}
                            </div>
                            <div class="form-group">
                                <label for="image"
                                       class=" col-form-label tm-text-primary">{{ __('New Profile Photo') }}</label>
                                <div id="input-group-image">
                                    <input id="image" type="file"
                                           class="form-control rounded-0"
                                           name="profile-photo">
                                </div>
                            </div>
                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-6">
                                    <button type="submit" class="btn btn-primary" id="btn-update-image"
                                            style="background-color:#009999; border: none; font-size: 15px ">
                                        {{ __('Update profile-photo') }}
                                    </button>
                                </div>
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

        function initFill(id) {
            $('#current_password').val('');
            $('#password').val('');
            $('#birthday').val('');
            $('#password-confirm').val('');
            $('#image').val('')

            $.ajax({
                url: `/api/personal/${id}/edit`,
                method: 'get',
                dataType: 'json',
                success: function (data) {

                    $('#name').val(data.name);
                    $('#birthday').val(data.birthday);
                    $('#preview-photo').append('<img src="{{ asset('/storage/images/'.Auth::user()->image) }}"  width="200" class="img-fluid img-thumbnail">');
                },
                error: function (data) {
                    if (data.status === 422) {
                        var errors = data.responseJSON.errors;
                        $.each(errors, function (key, value) {
                            console.log(value);
                        });
                    }
                },
            });
        }

        $(document).ready(function () {

            $('#datepicker').datepicker({format: 'mm/dd/yyyy'
            });

            let id = $('#edit-card').attr('data-id');

            initFill(id);

            $('#btn-update-password').on("click", function () {
                $('.is-invalid').removeClass('is-invalid');
                $(".invalid-feedback").remove();

                let current_password = $('#current_password').val();
                let password = $('#password').val();
                let password_confirmation = $('#password-confirm').val();

                $.ajax({
                    url: `/api/personal/password/${id}`,
                    type: 'PATCH',
                    data: {
                        _method: 'PATCH',
                        'current_password': current_password,
                        'password': password,
                        'password_confirmation': password_confirmation
                    },
                    dataType: 'json',
                    success: function (data) {
                        if (data.id) {
                            let id = data.id
                            initFill(id);
                            alert('Password has beed updated successfully');
                        }
                        // location.reload();
                    },
                    error: function (data) {
                        if (data.status === 422) {
                            var errors = data.responseJSON.errors;
                            console.log(errors);
                            $.each(errors, function (key, value) {
                                console.log(key);
                                if (key === 'current_password') {
                                    $('#current_password').addClass('is-invalid');
                                    let rowError = `<div class="invalid-feedback"> ${value[0]} </div>`
                                    $('#input-group-current_password').append(rowError);
                                } else if (key === 'password') {
                                    $('#password').addClass('is-invalid');
                                    let rowError = `<div class="invalid-feedback"> ${value[0]} </div>`
                                    $('#input-group-password').append(rowError);
                                } else if (key === 'password_confirmation') {
                                    $('#password-confirm').addClass('is-invalid');
                                    let rowError = `<div class="invalid-feedback"> ${value[0]} </div>`
                                    $('#input-group-password-confirm').append(rowError);
                                }
                            });
                        }
                    },
                });
            });

            $('#personal-info-update').on("click", function () {
                $('.name').removeClass('is-invalid');
                $('.birthday').removeClass('is-invalid');
                $(".invalid-feedback").remove();

                let name = $('#name').val();
                let birthday = $('#birthday').val();

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

            $('#btn-update-image').on("click", function () {
                $('.is-invalid').removeClass('is-invalid');
                $(".invalid-feedback").remove();

                let image = $('#image')[0].files[0];
                let formData = new FormData();

                formData.append('image', image);

                $.ajax({
                    url: `/api/personal/image/${id}`,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        if (data.id) {
                            let id = data.id
                            initFill(id);
                            alert('Image uploaded successfully');
                        }
                    },
                    error: function (data) {
                        if (data.status === 422) {
                            var errors = data.responseJSON.errors;

                            $.each(errors, function (key, value) {
                                if (key === 'image') {
                                    $('#image').addClass('is-invalid');
                                    let rowError = `<div class="invalid-feedback"> ${value[0]} </div>`
                                    $('#input-group-image').append(rowError);
                                }
                            });
                        }
                    },
                });
            });
        });
    </script>
@endsection
