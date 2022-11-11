@extends('layouts.app')

@section('style')
<link href="/css/login-style.css" rel="stylesheet" />
<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
@endsection
@section('content')
<div class="container-fluid curve-bakground">
    <div class="side-logo">
        <img src="/images/side-logo.png" />
    </div>
    <div class="row right-illustration">
        <!-- <div class="col-sm-2">
            
        </div> -->
        <div class="col-sm-12">
            <div class="container">
                <div class="row">
                    <div class="col-sm-5"></div>
                    <div class="col-sm-4" style="padding:0px">
                        <div class="login_card card">
                            <div class=" d-flex justify-content-center">
                                <img src="/images/unnamed.png" class="brand_logo" alt="Logo">
                            </div>

                            <h2>SIGN IN</h2>

                            <!-- login form starts here -->
                            <div class="card-body">

                                <form method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="input-group mb-2 mr-sm-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fas fa-user"></i></div>
                                                </div>
                                                <input id="email" placeholder="Email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                            </div>
                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 16px;">
                                        <div class="col-sm-12">
                                            <div class="input-group mb-2 mr-sm-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fas fa-lock"></i></div>
                                                </div>
                                                <input id="password" type="password" class="form-control input_pass @error('password') is-invalid @enderror" placeholder="Password" name="password" required autocomplete="current-password">
                                            </div>
                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 16px;">
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="remember">
                                                    {{ __('Remember Me') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <button type="submit" class="btn btn-primary btn-block" id="generate-offer-report">Login</button>
                                            @if (Route::has('password.request'))
                                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                                {{ __('Forgot Your Password?') }}
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- login form ends here -->
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection