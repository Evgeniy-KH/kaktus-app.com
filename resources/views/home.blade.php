@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-4 col-12 mb-5">
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                        <h2 class="tm-text-primary mb-3" style="text-align: center; font-weight: bold;">{{ __('You are logged in!') }}</h2>
            </div>
        </div>
    </div>
</div>
@endsection
