<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body>

@auth
<!-- menu -->
<div class="modal fade" id="menu" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="menu" aria-hidden="true">
  <div class="modal-dialog modal-xl m-0">
    <div class="modal-content min-vh-100 rounded-0">
      <div class="modal-header border-0">
        <h5 class="modal-title" id="menu">BooksReam</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <ul class="list-group list-group-flush">
          <li class="list-group-item">
              <div class="d-flex align-items-center h-100 py-1">
                  <span class="d-flex align-items-center pr-2">
                      <img src="https://img.icons8.com/android/24/000000/user.png" style="width: 17px;" />
                  </span>
                  <a href="{{ url('/profile') }}" class="text-decoration-none stretched-link text-muted">
                      Profile
                  </a>
              </div>
          </li>
          <li class="list-group-item">
              <div class="d-flex align-items-center h-100  py-1">
                  <span class="d-flex align-items-center pr-2">
                      <img src="https://img.icons8.com/android/24/000000/home.png" style="width: 17px;" />
                  </span>
                  <a href="{{ url('/home') }}" class="text-decoration-none stretched-link text-muted">
                      Home
                  </a>
              </div>
          </li>
          <li class="list-group-item">
              <div class="d-flex align-items-center h-100  py-1">
                  <span class="d-flex align-items-center pr-2">
                      <img src="https://img.icons8.com/ios-filled/50/000000/book.png" style="width: 17px;" />
                  </span>
                  <a href="{{ route('bookUploadForm') }}" class="text-decoration-none stretched-link text-muted">
                      Add Book
                  </a>
              </div>
          </li>
          <li class="list-group-item">
              <div class="d-flex align-items-center h-100  py-1">
                  <span class="d-flex align-items-center pr-2">
                      <img src="https://img.icons8.com/ios-filled/50/000000/location-off.png" style="width: 17px;" />
                  </span>
                  <a href="{{ route('locationUploadForm') }}" class="text-decoration-none stretched-link text-muted">
                      Manage Location
                  </a>
              </div>
          </li>
          <li class="list-group-item">
              <div class="d-flex align-items-center h-100  py-1">
                  <span class="d-flex align-items-center pr-2">
                      <img src="https://img.icons8.com/material-rounded/96/000000/settings.png" style="width: 17px;" />
                  </span>
                  <a href="{{ url('/setting') }}" class="text-decoration-none stretched-link text-muted">
                      Setting
                  </a>
              </div>
          </li>
          <li class="list-group-item">
              <div class="d-flex align-items-center h-100  py-1">
                  <span class="d-flex align-items-center pr-2">
                      <img src="https://img.icons8.com/material-outlined/96/000000/help.png" style="width: 17px;" />
                  </span>
                  <a href="{{ url('/help') }}" class="text-decoration-none stretched-link text-muted">
                      Help
                  </a>
              </div>
          </li>
          <li class="list-group-item">
              <div class="d-flex align-items-center h-100 py-1">
                  <span class="d-flex align-items-center pr-2">
                      <img src="https://img.icons8.com/metro/64/000000/exit.png" style="width: 17px;" />
                  </span>
                  <a href="#" class="text-decoration-none stretched-link text-muted" 
                      onclick="event.preventDefault();  document.getElementById('logout-form').submit();">
                      Logout
                  </a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                      @csrf
                  </form>
              </div>
          </li>
      </ul>
    </div>
    <div class="modal-footer border-0">

    </div>
    </div>
  </div>
</div>
<!-- End Menu -->
 @endauth


<div class="sticky-top">
@yield('navbar')
</div> 


<main class="">
    @yield('content')
</main>


<div id="snackbar">Message Sent</div>

@yield('footer')

<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>

<script>

// auto suggestion
$(document).ready(function(){

    var input = $('#autocomplete-input');
    var list = $('.autocomplete-sug');
    input.keyup(function() {
        var str = $(this).val();
        list.empty();
        if (str !== '') {
            suggestion(str);
            $('.autocomplete-sug').delegate('li','click', function() {
                input.val($(this).html());
                $('.search-get').submit();
            });
        }
    });
        
    function suggestion (str) {
        
        var url = '{{ route("autocomplete") }}';             
        var list = $('.autocomplete-sug');
        jQuery.ajax({
            url: url,
            type: "post",
            data: {
                'str': str,
                "_token": "{{ csrf_token() }}",
                },   
            success: function(html) { 
                list.append(html);
            },
            error: function (error) {
                console.log(error);
            },
        });
    }
  
});           

</script>
@yield('script')

<script>
$('#close-search').on('click', () => {
    $('#search-body').hide();
});

$('.search-nav').on('click', () => {
    $('#search-body').show();
});

$('.search-submmit').on('click', function() {
        $('.search-get').submit();
})

</script>
</body>
</html>
