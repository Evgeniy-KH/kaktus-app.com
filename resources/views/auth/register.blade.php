@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-4 col-12 mb-5">
                <h2 class="tm-text-primary mb-3" style="text-align: center; font-weight: bold;">Register</h2>
                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}" class="tm-contact-form mx-auto">
                        @csrf

                        <div class="form-group">
                            <label for="name"
                                   class=" col-form-label text-md-end tm-text-primary">{{ __('Name') }}</label>
                            <div>
                                <input id="name" type="text"
                                       class="form-control rounded-0 @error('name') is-invalid @enderror" name="name"
                                       value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email"
                                   class=" col-form-label  tm-text-primary">{{ __('Email Address') }}</label>
                            <div>
                                <input id="email" type="email"
                                       class="form-control rounded-0 @error('email') is-invalid @enderror" name="email"
                                       value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class=" col-form-label  tm-text-primary">{{ __('Password') }}</label>
                            <div>
                                <input id="password" type="password"
                                       class="form-control rounded-0 @error('password') is-invalid @enderror"
                                       name="password" required autocomplete="new-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm"
                                   class=" col-form-label tm-text-primary">{{ __('Confirm Password') }}</label>
                            <div>
                                <input id="password-confirm" type="password" class="form-control rounded-0"
                                       name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary"
                                        style="background-color: #0397ed; border: none">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
