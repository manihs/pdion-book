@extends('layouts.app')

@section('navbar')

@include('component.nav')
<div class="overflow-auto p-0 p-sm-0 p-md-0 p-lg-0 col-12 col-sm-11 col-md-7 col-lg-6 mx-auto">
    <div class="">
        <div class="card-header rounded-0 d-flex align-items-center py-2 px-1 bg-info">
            <a href="{{ url('message') }}" class="p-2">
                <img src="https://img.icons8.com/android/24/ffffff/back.png" width="20" height="20" />
            </a>
            <div class="avatar">
                <img src="{{ $user->image }}" alt="" srcset="" class="rounded-circle" width="40px">
            </div>
            <h4 class="name text-white text-capitalize ml-3 mb-0">
                {{ $user->name }}  
            </h4>
            <span class="text-white typing mx-3" style="display:none;">typing</span>
        </div>
    </div>
</div>

@endsection

@section('content')
<div class="overflow-auto p-0 p-sm-0 p-md-0 p-lg-0 col-12 col-sm-11 col-md-7 col-lg-6 mx-auto">
    <div class="p-0" id="message-wrapper">
        <div class="card rounded-0 border-0" style="background-image: url('https://mir-s3-cdn-cf.behance.net/project_modules/disp/c8dbbe73426833.5c08f56351b26.png');background-size: contain;">
            <div class="card-body px-3 px-md-5" id="message-body" style="min-height:72vh;">  
                @include('message.message')
            </div>
        </div>
        <div class="card-footer text-muted bg-white d-flex p-0">
            <input type="text" name="message" id="message-input" class="w-100 p-3 px-4 rounded-pill border-0" placeholder="Type A Message" autocomplete="off" spellcheck="true">
            <button type="button" class="btn btn-success rounded-0">Success</button> 
        </div>
    </div>
</div>
@endsection

@section('script')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>

$(document).ready(function(){

    var recMessageId = {{ $user->id }};

    // -------------------------- | Update Notification | --------------------------------------
    function update_message () {
        jQuery.ajax({ 
            url:  "{{ route('messagecount') }}",
            type: "post",
            data: { 
                "_token": "{{ csrf_token() }}", 
                },
            success: function(data) { 
                if(data !== '') {
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

    // ----------------------------| load Old Message | ------------------------------------------

    infiniteScroll = function() {

        if($(window).scrollTop() <= 0) {

            var old_height = $(document).height();  //store document height before modifications
            var old_scroll = $(window).scrollTop();
            
            var track = $('.user-message').first().data('track');
            var reciver = {{ $user->id }};
            var url = "{{ route('ajax.old.message') }}"
            $.ajax({
                type: "POST",
                url: url,
                data: {
                'track': track,
                "_token": "{{ csrf_token() }}",
                'id': reciver,
                },   
                success: function(data)
                {
                   $('.card-body').prepend(data.html);
                   $(document).scrollTop(old_scroll + $(document).height() - old_height);
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }
     
    }
       
    $(window).on('scroll',infiniteScroll);
    $(window).on('touchmove',infiniteScroll);
    
    // ------------------------------- | scrolll Bottom  | ----------------------------------------


    function scrollBottom (){
    
        $("html, body").animate({ 

            scrollTop: $('#message-body').get(0).scrollHeight 

        });  
        
    }

    // ------------------------------------------------------------------------------------------------ 

    $(document).on('keyup','#message-input',function(e) {

        let channel = Echo.private( 'chat.' + recMessageId );

        setTimeout( () => {
            channel.whisper('typing', {
            typing: true,
            sender: {{ Auth::user()->id }},
            reciver: recMessageId,
            })
        }, 300)

        Echo.private( 'chat.' + recMessageId ).whisper('seen', { seen: true }); 
        
        var message = $(this).val();

        if(e.keyCode == 13 && recMessageId != '' && message != '') {

            $(this).val('');

            var url = '{{ route("sendmessage") }}';

            jQuery.ajax({
                url: url,
                type: "post",
                data: {
                    'to': recMessageId,
                    'message':message,
                    "_token": "{{ csrf_token() }}",
                    },   
                success: function(data) { 
                    console.log(data);
                    $('#message-body').append(data.data);
                    
                    scrollBottom();

                },
                error: function (error) {
                    console.log(error);
                },
            });

        }
    });

    // ---------------------------------------------------------------------------------------------------------------------------------------------------------
    
    update_message ();

    scrollBottom ();

    setInterval(function() {
        $('.typing').hide();
        if(document.hasFocus()){
            Echo.private( 'chat.' + recMessageId ).whisper('seen', { seen: true }); 
        }
    }, 2000);

 
  Echo.private( `chat.{{ Auth::user()->id }}` )
        
    .listenForWhisper('seen', (e) => {
        $('.mseen').css('background','green');   
    })

    .listenForWhisper('typing', (e) => {
        if(e.reciver == {{ Auth::id() }}) {
            $('.typing').show();    
        }
    })
  
    .listen('MessageEvent', (e) => {

        console.log( e.sender.id === recMessageId )
    
        if(e.sender.id === recMessageId ){
           
            $('#message-body').append(`   

            <div class="d-flex justify-content-start my-2 user-message" data-track=" `+e.message.id+`">
                <div class="d-flex flex-column align-items-start">
                    <div class="d-flex">
                        <img src="`+e.sender.avatar+`" alt="" srcset="" class="rounded-circle mx-2" width="30px" height="30px">
                        <div href="#" class="btn btn-primary rounded-pill px-4"> 
                        `+e.message.text+`
                        </div>
                    </div>
                    <span class="text-muted my-1" style="font-size: 9px;"> 
                    {{ date("d F Y g:i A", strtotime(`+e.message.created_at+`)) }}
                    </span>
                </div>
            </div>

            `);

            jQuery.ajax({ 
                url:  "{{ route('messageseen') }}",
                type: "post",
                data: { 
                    "_token": "{{ csrf_token() }}", 
                    "sender": e.sender.id,
                    "to": {{ auth::user()->id }}
                    },
                success: function(data) { 

                    // 
                    if(data){
                        if(document.hasFocus()){
                            Echo.private( 'chat.' + recMessageId ).whisper('seen', { seen: true }); 
                        }
                    }
                    // 

                },
                error: function (error) {
                        console.log(error);
                },
            }); 

            scrollBottom();
          

        }else{

            var pending = parseInt($('[data-uuid="'+e.sender.id+'"]').find(".badge" ).html());

            if ( pending ) {

                $doodle = $('[data-uuid="'+e.sender.id+'"]');
                $doodle.prependTo('.userlist');
                $doodle.find(".badge" ).html(pending + 1);
                $doodle.find(".text-message" ).html(e.message.text);

                update_message();

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

                update_message();
               
            }           
        } 
    });

});

  </script>

@endsection