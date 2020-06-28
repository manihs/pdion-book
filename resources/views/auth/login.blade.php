@extends('layouts.app')

@section('navbar')

<div class="text-right py-3 px-4 d-flex justify-content-between align-items-center">
    <a class="navbar-brand mx-2" href="{{ url('/') }}">Books Ream</a>
    @if (Route::has('login'))
        <div class="top-right links">
            @auth
                <a href="{{ url('/home') }}" class="px-3 py-3 f-16">Home</a>
            @else
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="px-3 py-3 f-16">Register</a>
                @endif
            @endauth
        </div>
    @endif
</div>

@endsection

@section('content')
<div class="container-fluid">
        <div class="row pt-5 pb-5">
            <div class="col-6 d-none d-md-block">
                <div class="row align-items-center">
                    <div class="col-8 m-auto">
                        <img src="/images/study.png" alt="" srcset="" width="100%">

                        <div class="text-center mt-3">
                            <a class="nav-link text-dark" href="#">Create a account ?</a>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 d-flex align-items-center">

                <form action="{{ route('login') }}" method="post" class="col-12 col-sm-12 col-md-10  col-lg-8">
                    @csrf
                    <h2 class="mb-4 text-bold">Log In</h2>
                    <div class="form-group mb-4">
                        <input type="text" placeholder="Email" name="email" 
                                class="rounded-0 username form-control p-4 border-bottom @error('email') is-invalid @enderror" id="email" required autocomplete="email" autofocus>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>    
                            </span>
                        @enderror
                    </div>
                    <div class="form-group mb-4">
                        <input type="password" placeholder="password" name="password"  
                                class="rounded-0 password form-control p-4 border-bottom @error('password') is-invalid @enderror" id="password" required autocomplete="current-password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror                    
                    </div>
                    <div class="form-group form-check d-flex justify-content-between">
                        <span>
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                            <label class="form-check-label" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </span>
                        @if (Route::has('password.request'))
                        <a class="nav-link text-dark p-0" href="{{ route('password.request') }}">Forgot password</a>
                        @endif
                    </div>
                    <button type="sumbit" class="btn rounded-0 p-2 pl-3 pr-3 mt-3 btn-outline-primary">Log In</button>
                </form>

            </div>
        </div>
    </div>

@endsection


@section('footer')
@include('component.footer')
@endsection
