@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-4 col-12 mb-5">
                <h2 class="tm-text-primary mb-3" style="text-align: center; font-weight: bold;">Login</h2>
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}" class="tm-contact-form mx-auto">
                        @csrf

                        <div class="form-group">
                            <label for="email"
                                   class="col-form-label tm-text-primary">{{ __('Email Address') }}</label>

                            <div>
                                <input id="email" type="email"
                                       class=" form-control rounded-0 @error('email') is-invalid @enderror" name="email"
                                       value="{{ old('email') }}" required autocomplete="email" autofocus>
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password"
                                   class="col-form-label text-md-end tm-text-primary">{{ __('Password') }}</label>

                            <div>
                                <input id="password" type="password"
                                       class="form-control  rounded-0 @error('password') is-invalid @enderror"
                                       name="password"
                                       required autocomplete="current-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember"
                                           id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label tm-text-primary" for="remember" style="font-weight: 800;">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary" style="background-color: #0397ed; border: none">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link tm-text-primary" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                            <div class="flex items-center justify-end mt-4">
                                <a class="btn" href="{{ url('auth/facebook') }}"
                                   style="background: #3B5499; color: #ffffff; padding: 10px; width: 100%; text-align: center; display: block; border-radius:3px;">
                                    Login with Facebook
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
