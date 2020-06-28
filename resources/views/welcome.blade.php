@php $_true = False @endphp
@extends('layouts.app')

@section('navbar')

<div class="text-right py-3 px-4 bg-light">
    @if (Route::has('login'))
        <div class="top-right links">
            @auth
                <a href="{{ url('/home') }}" class="px-3 py-3 f-16">Home</a>
            @else
                <a href="{{ route('login') }}" class="px-3 py-3 f-16">Login</a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="px-3 py-3 f-16">Register</a>
                @endif
            @endauth
        </div>
    @endif
</div>


<!-- search -->
<div id="search-body" class="position-fixed top bottom left right bg-light" style="z-index:1; display:none;">
    <nav class="mob-navbar shadow-sm">
        <div class="row h-100">
            <div class="col-2 p-0 d-flex align-items-center">
                <button class="w-100 h-100 bg-transparent border-0" id="close-search">
                    <img src="https://img.icons8.com/android/24/ffffff/back.png" style="width: 20px;" />
                </button>
            </div>
            <div class="col-8 p-0 d-flex align-items-center">
               
                    <input type="text" name="query-m" id="autocomplete-input" autocomplete="off" class="w-100 h-100 p-3" placeholder="Find Book" value="{{ $query  ?? '' }}">
                
            </div>
            <div class="col-2 p-0 d-flex align-items-center">
                <button class="w-100 h-100 bg-transparent border-0 search-submmit" id="search-button">
                        <img src="https://img.icons8.com/android/50/ffffff/search.png" style="width: 20px;" />
                </button>
            </div>
        </div>
    </nav>
    <div class="ui-autocomplete p-1">
        <ul class="list-group list-group-flush autocomplete-sug">

        </ul>
    </div>
</div>
<!-- End search -->
@endsection



@section('content')
  
<form action="{{ route('result') }}" method="get" class="search-get">
       
    <input type="hidden" name="query">        
       
</form>

<section class="">
    <div class="my-5 col-11 col-sm-10 col-md-8 col-lg-6 mx-auto">
        <div class="text-center">
            <h1 class="text-muted mb-4">Books Ream</h1>
        </div>
        <div class="d-flex shadow-sm">
            <input type="text" name="search" id="" class="search-input-text p-2 flex-grow-1" placeholder="Search Book">
            <div class="col-2 p-0 d-flex align-items-center">
                <button class="w-100 h-100 bg-success border-0 search-submmit" id="">
                        <img src="https://img.icons8.com/android/50/ffffff/search.png" style="width: 20px;" />
                </button>
            </div>
        </div>
        <div class="space-30"></div>
    </div>
</section>
@endsection


@section('footer')
@include('component.footer')
@endsection

@section('script')

<script>

    $( "#close-search" ).click(function(){
        var value =     $('input[name=query-m]').val();
        $('input[name=search]').val(value);
       
    });

    $( '.search-input-text' ).focus(function() {
        $('input[name=query-m]').val( $('input[name=search]').val() );
        $("#search-body").show();
        $("#autocomplete-input").focus();
    });

    $('.search-submmit').on('click', function() {
        $('input[name=query]').val($('input[name=query-m]').val());
        $('.search-get').submit();
    })

</script>

@endsection
