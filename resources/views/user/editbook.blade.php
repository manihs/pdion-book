@extends('layouts.app')

@section('navbar')

@include('component.nav')

@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="p-sm-0 p-md-0 p-lg-0 col-12 col-sm-11 col-md-7 col-lg-6">
            <div class="card">
                <div class="card-header">Upload Book Form</div>

                <div class="card-body">
                     @if($post)   
                    <form action="{{ route('bookUploadStore') }}" method="post">
                        @csrf
                        <input type="text" value="{{  $post->title }}" name="title" id="" placeholder="title" class="w-100 p-2 m-2">
                        <input type="text" value="{{  $post->subtitle }}"  name="subtitle" id="" placeholder="subtitle" class="w-100 p-2 m-2">
                        <input type="text" value="{{  $post->name }}"  name="isbn" id="" placeholder="isbn" class="w-100 p-2 m-2">
                        <input type="text" value="{{  $post->price }}"  name="price" id="" placeholder="price" class="w-100 p-2 m-2">
                        <input type="text" value="{{  $post->Author }}"  name="author" id="" placeholder="author" class="w-100 p-2 m-2">
                        <input type="text" value="{{  $post->Publisher }}"  name="publisher" id="" placeholder="publisher" class="w-100 p-2 m-2">
                        <input type="text" value="{{  $post->language }}"  name="language" id="" placeholder="language" class="w-100 p-2 m-2">

                        <button type="submit">upload</button>
                    
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('script')
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