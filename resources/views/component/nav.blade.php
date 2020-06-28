
<div class="" style="background: #127681;">
    <div class="col-12 col-sm-10 col-md-7 col-lg-6 mx-auto mob-navbar">
        <nav class="row mx-auto h-60px">
            @guest
                <div class="col-7 d-flex align-items-center pl-3">
                    <a class="navbar-brand text-white mx-2" href="{{ url('/') }}">Books Ream</a>
                </div>
                <div class="col-5 p-0 d-flex align-items-center">
                    <button class="w-100 h-100 bg-transparent border-0 search-nav search-nav">
                        <img src="https://img.icons8.com/android/50/ffffff/search.png" style="width: 20px;" />
                    </button>
                    <a href="{{ route('login') }}" type="button" class="btn btn-outline-warning px-4 rounded-0 mx-3">Login</a>
                </div>
            @endguest
            @auth
                <div class="col-2 p-0 d-flex align-items-center">
                    <button class="w-100 h-100 bg-transparent border-0" data-toggle="modal" data-target="#menu">
                        <img src="https://img.icons8.com/metro/26/ffffff/menu.png" style="width: 20px;" />
                    </button>
                </div>
                <div class="col-5 d-flex align-items-center p-0">
                    <a class="navbar-brand text-white mx-2" href="{{ url('/') }}">Books Ream</a>
                </div>
                <div class="col-5 p-0 d-flex align-items-center">
                    <button class="w-100 h-100 bg-transparent border-0 search-nav">
                        <img src="https://img.icons8.com/android/50/ffffff/search.png" style="width: 20px;" />
                    </button>
                <a class="w-100 h-100 d-flex justify-content-center align-items-center p-2" id="notification-nav" href="{{ url('message') }}">
                    <span class="position-relative message-notification">
                        <img src="https://img.icons8.com/material-outlined/50/ffffff/new-message.png" style="width: 20px;" />
                    </span>
                </a>
                <a class="w-100 h-100 d-flex justify-content-center align-items-center p-2" id="message-nav" href="#notification">
                    <img src="https://img.icons8.com/android/100/ffffff/appointment-reminders.png" style="width: 20px;" />
                </a>
                </div>
            @endauth
        </nav>
    </div>
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
                    <form action="{{ route('result') }}" method="get" class="search-get w-100 h-100">
                        <input type="text" name="query" id="autocomplete-input" autocomplete="off" class="w-100 h-100 p-3" placeholder="Find Book" value="{{ $query  ?? '' }}">
                    </form>
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