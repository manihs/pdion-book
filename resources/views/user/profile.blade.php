@extends('layouts.app')

@section('navbar')

@include('component.nav')

@endsection

@section('content')
<div class="col-12 col-sm-11 col-md-8 col-lg-5 mt-2 mx-auto">
    <div class="avatar text-center mt-5">
        <img src="https://picsum.photos/200/300?nocache=<?php echo microtime(); ?>" alt="{{ asset($user->image) }}" class="rounded" width="30%">
        <h5 id="" class="mt-2 mb-3">{{ $user->name }}</h5>
    </div>
    <div class="d-flex">
        <button type="button" class="btn btn-primary flex-grow-1" data-toggle="modal" data-target="#profilePic">
            Edit Profile
        </button>
    </div>
    <!-- Detail -->
    <div class="mt-3">
        <p class="f-16"> 📕&nbsp; 
            <span class="text-info">
                Email
            </span>&nbsp;
            <span class="text-muted font-weight-bold">
                @if ( $user->email != '' ) {{ $user->email }}  @else Pending @endif
            </span>
        </p>
        <p class="f-16"> 📕&nbsp; 
            <span class="text-info">Contact</span>&nbsp;
            <span class="text-muted font-weight-bold">
                @if( $user->contact != '') {{ $user->contact }} @else Pending... @endif
            </span>
        </p>
        <p class="f-16"> 📕&nbsp; 
            <span class="text-info">Verification</span>&nbsp;
            <span class="text-muted font-weight-bold">
                @if( $user->email_verified_at != '') {{ $user->email_verified_at }} @else Pending... @endif
            </span>
        </p>
        <p class="f-16"> 📕&nbsp; 
            <span class="text-info">Description</span>&nbsp;
            <span class="text-muted font-weight-bold">
            @if($user->description != '') {{ $user->description }} @else Pending... @endif
            </span>
        </p>
        <p class="f-16"> 📕&nbsp; <span class="text-info">Profile Type
            </span>&nbsp;
            <span class="text-muted font-weight-bold">@if($user->user_type != '') {{ $user->user_type }} @else Pending @endif
            </span>
        </p>
    </div>
    <!-- End Detail -->
    <div class="py-2 border-bottom border-top d-flex align-items-center">
    <h6 class="flex-grow-1 text-muted m-0">All Book</h6>
      <div class="">
        <a href="{{  route('bookUploadForm') }}" class="btn btn-warning rounded-0">Add Book</a>
        <a href="{{  route('locationUploadForm') }}" class="btn btn-info rounded-0">Add Location</a>
      </div>
    </div>
    <!-- Product -->
    <div class="overflow-auto col-12 mx-auto" id="post-wraper">
      @include('post')
    </div>
    <!-- End Product -->

</div>

<!-- Modal -->
<div class="modal fade" id="profilePic" tabindex="-1" role="dialog" aria-labelledby="profilePic" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="profilePic">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary my-modal" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary save">Save changes</button>
      </div>
    </div>
  </div>
</div>
<!-- End Modal -->
@endsection


@section('script')

<script>

$(document).ready(function(){
    var page = 1;
   

    infiniteScroll = function() {
    
        if($(window).scrollTop() + 1 + $(window).height() >= $(document).height()) {
            if (page <= {{ $posts->lastPage() }}){
            page++;
            url = window.location.href+'&page='+ page;

                if(url.includes("?")){

                    loadMoreData(page, window.location.href+'&page='+ page);
                    
                }else{

                    loadMoreData(page, window.location.href+'?page='+ page);
                    
                }

            }
        } 
    }

    function filter_data()
    {
        var query = $('input[name=query]').val();
        var location = $('input[name=location]').val();

        var urldata =[];
       
        if (query.length != 0) {
            urldata.push('query='+query);
        }

        if (location.length != 0) {
            urldata.push('location='+location);
        }

        if (query.length != 0 || location.length != 0) {

            window.history.pushState(null, null, 'result?'+urldata.join('&'));

        } else {

            window.history.pushState(null, null, 'result?'+urldata.join('?'));

        }

        $( "#post-wraper" ).empty();

        page = 1

        url = window.location.href+'&page='+ page;

        loadMoreData (page, url)
    }

    function loadMoreData (page, url) {

        var location = $('input[name=location]').val();

        console.log(url)
      
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        
        jQuery.ajax({
            url: url,
            type: "get",
            beforeSend: function(){ //This is your loading message ADD AN ID
                $('#content').append('<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>');
            },
            complete: function(){ //remove the loading message
                $('.spinner-border').remove();
            },
            success: function(html) { // success! YAY!! Add HTML to content container
                $('#post-wraper').append(html.html); 
                $('.spinner-border').remove();  
            },
            error: function (error) {
                console.log(error);
            },
        
            });
        }      

    $(window).on('scroll',infiniteScroll);
    $(window).on('touchmove',infiniteScroll);

    $('.apply-filter').on('click', function () {
        filter_data();
        
    })

    $("form").submit(function(e) {

        e.preventDefault(); // avoid to execute the actual submit of the form.

        var form = $(this);
        var url = form.attr('action');
        alert(form.attr('action'));
        alert(form.attr('action'));
        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(), // serializes the form's elements.
            success: function(data)
            {
                console.log(data); // show response from the php script.
            },
            error: function (error) {
                console.log(error);
            }
            });


    });

    // save

    $('.save').on('click', function() {
        alert();
        $( ".my-modal" ).trigger( "click" );
    });

});           
</script>


@auth
<script>

    $(document).ready(function(){
        function update_message () {
            jQuery.ajax({ 
                url:  "{{ route('messagecount') }}",
                type: "post",
                data: { 
                    "_token": "{{ csrf_token() }}", 
                    },
                success: function(data) { 
                    if(data !== '' && data !== 0) {
                        $('.message-notification').append(`
                            <span class="position-absolute" style="top: 9px; right: -6px">
                                <div class="text-white unseen-messages rounded-pill px-2 py-1 bg-info" style="font-size: 8px;">`+data+`</div>
                            </span>
                        `);
                    }
                },
                error: function (error) {
                    console.log(error);
                },
            }); 
        }
        update_message();
        setInterval(function() {
            $(".text-message").show();    
            $(".show-typing").hide();    
        }, 2000);
    Echo.private( `chat.{{ Auth::user()->id }}` ) 
        .listenForWhisper('seen', (e) => {
            console.log('seen');
            $('.mseen').css('background','green');   
        
        })
        .listenForWhisper('typing', (e) => {
            console.log(e);
            $doodle = $('[data-uuid="'+e.userid+'"]')
            $doodle.find(".text-message").hide();    
            $doodle.find(".show-typing").show();    
        })
        .listen('MessageEvent', (e) => {       
            update_message();
            var pending = parseInt($('[data-uuid="'+e.sender.id+'"]').find(".badge" ).html());
            if ( pending ) {
                $doodle = $('[data-uuid="'+e.sender.id+'"]');
                $doodle.prependTo('.userlist');
                $doodle.find(".badge" ).html(pending + 1);
                $doodle.find(".text-message" ).html(e.message.text);
            } else {
                console.log(e);
                $doodle = $('[data-uuid="'+e.sender.id+'"]');
                $doodle.prependTo('.userlist');
                $doodle.find(".text-message" ).html(e.message.text);
                $doodle.find('.seen').html(`
                <div class="messages-time text-info mb-1" style="font-size:10px;">12: 03 Pm</div>
                <div class="text-white">
                    <div class="rounded-pill badge p-1 px-2" style="background: green; font-size:10px; display: inline-block;">
                        1
                    </div>
                </div>
                `);
            }           
        });
    });

</script>
@endauth

@endsection