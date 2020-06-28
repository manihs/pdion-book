@extends('layouts.app')

@section('navbar')

@include('component.nav')

@endsection

@section('content')
<div class="p-sm-0 p-md-0 p-lg-0 col-12 col-sm-11 col-md-7 col-lg-6 mt-2 mx-auto">

    <div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom">
        <h6 class="text-muted m-0">Add Location</h6>
        <button type="button" class="btn btn-primary rounded-0">View All location</button>
    </div>

    <div class="card rounded-0">

        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
                <p class="text-muted">Enter The Place </p>
            <form action="{{ route('locationUploadStore') }}" method="post">

                @csrf

                <div class="form-group">
                    <input type="text" class="form-control rounded-0" placeholder="Location" name="location">       
                </div>

                <div class="form-group d-flex justify-content-center">
                    <button type="submit" class="btn btn-success rounded-0 btn-mw">Add Location</button>
                </div>

            </form>
            <p class="text-info">Note</p>
            <p class="text-muted">The Location i.e station, college, landmark etc where People can find You with the location you specifiied</p>

        </div rounded>
    </div>

    <div class="d-flex flex-wrap mt-2">
    
    @foreach ($au_locations->take(8) as $au_location)
        <a type="button" href="{{ route('locationDeleteStore',['id'=>$au_location->location_id ]) }}" class="close p-2 m-1 bg-primary text-white rounded" aria-label="Close">
        <span> {{ $au_location->location }} </span> 
          <span aria-hidden="true">&times;</span>
        </a>
    @endforeach
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
