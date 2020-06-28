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
                    <a href="{{ route('login') }}" class="px-3 py-3 f-16">Login</a>
                @endif
            @endauth
        </div>
    @endif
</div>

@endsection

@section('content')

<div class="container-fluid py-4">
    <div class="row">
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
            <form action="{{ route('register') }}" method="post" class="col-12 col-sm-12 col-md-10  col-lg-8">
                @csrf
                <h2 class="mb-4 text-bold">Sign up</h2>
                <!--  -->
                <div class="form-group mb-4">
                    <input type="text" placeholder="username" class="p-4 rounded-0 username form-control border-bottom @error('name') is-invalid @enderror" 
                            id="exampleInputEmail1"  name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>    
                            </span>
                        @enderror
                </div>
                <!--  -->
                <div class="form-group mb-4">
                    <input id="email" type="email" placeholder="Email" class="p-4  rounded-0 mail form-control border-bottom @error('email') is-invalid @enderror" 
                            name="email" value="{{ old('email') }}" required autocomplete="email">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>    
                            </span>
                        @enderror
                </div>
                <!--  -->
                <div class="form-group mb-4">
                    <input id="password" type="password" placeholder="password" class="p-4 rounded-0 password form-control  border-bottom  @error('password') is-invalid @enderror" 
                            name="password" required autocomplete="new-password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>    
                            </span>
                        @enderror
                </div>
                <!--  -->
                <div class="form-group mb-4">
                    <input id="password-confirm" type="password" placeholder="confirm password" class="p-4  rounded-0 password form-control  border-bottom" 
                            name="password_confirmation" required autocomplete="new-password">
                   
                </div>
                <!--  -->

                <button type="submit" class="btn rounded-0 p-2 pl-4 pr-4 mt-3 btn-outline-primary">Sign up</button>
            </form>

        </div>
    </div>
</div>


@endsection

@section('footer')
@include('component.footer')
@endsection