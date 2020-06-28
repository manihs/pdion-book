@extends('layouts.app')

@section('navbar')

@include('component.nav')
<div class="p-0 col-12 col-sm-10 col-md-7 col-lg-6 mx-auto">
<div class="d-flex bg-white shadow-sm" style="height: 60px;">
    <button type="button" class="btn btn-white rounded-0 flex-grow-1 border-right" data-toggle="modal" data-target="#locationModel">
      Location
    </button>
    <button type="button" class="btn btn-white rounded-0 flex-grow-1" data-toggle="modal" data-target="#shortModel">
      Short By
    </button>
</div>
</div>
@endsection

@section('content')
@include('component.filter')

<div class="overflow-auto p-sm-0 p-md-0 p-lg-0 col-12 col-sm-11 col-md-7 col-lg-6 mx-auto" id="post-wraper">
   @include('post')
</div>
@endsection


@section('script')

<script>
$(document).ready(function(){

  var page = 1;
  var url = '';

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
        beforeSend: function(){ 
            $('#content').append('<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>');
        },
        complete: function(){ 
            $('.spinner-border').remove();
        },
        success: function(html) { 
            $('#post-wraper').append(html.html); 
            $('.spinner-border').remove();  
            console.log(html.html);
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

  });

</script>

<script>
$(document).ready(function(){

    $("form.ajax-message").submit(function(e) {
        e.preventDefault(); 
        var form = $(this);
        var url = form.attr('action');
      
        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(), 
            success: function(data)
            {
                $('button.close').click();
                var x = $("#snackbar");
                x.html('Message Sent :)')
                x.addClass("tshow");
                setTimeout(function(){ x.removeClass("tshow"); }, 3000);
            },
            error: function (error) {
                var x = $("#snackbar");
                x.html('Login Needed')
                x.addClass("tshow");
                setTimeout(function(){ x.removeClass("tshow"); }, 3000);
            }
        });
    });

})
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