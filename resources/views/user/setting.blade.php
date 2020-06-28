@extends('layouts.app')

@section('navbar')

@include('component.nav')

@endsection

@section('content')
<div class="col-11 col-sm-11 col-md-8 col-lg-5 mt-2 mx-auto py-4">
    <h5 class="text-muted font-weight-bold mb-3">Settiing</h5>
    <!-- name -->
    <div>
        <div class="d-flex justify-content-between py-2 align-items-center border-bottom border-top">

            <h6 class="text-muted f-16 "><span class="mr-2" style="font-size: 25px;">👨</span> Name</h6> 

            <button class="btn btn-primary rounded-0" type="button" data-toggle="collapse" data-target="#name" aria-expanded="false" aria-controls="collapseExample">
                Edit
            </button>

        </div>   

        <div class="collapse" id="name">
            <div class="card rounded-0 card-body">
                <form action="{{ route('editProfile') }}" method="get">
                    @csrf
                    <div class="form-group">
                        <input type="text" class="form-control" name="username" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="{{ $user->name }}">
                    </div>
                
                    <button type="submit" class="btn btn-primary rounded-0">Change</button>
                </form>
            </div>
        </div>
    </div>
    <!-- mail -->
    <div>
        <div class="d-flex justify-content-between py-2 align-items-center border-bottom border-top">

            <h6 class="text-muted f-16"><span class="mr-2" style="font-size: 25px;">👨🏼‍💻</span> Email</h6>

            <button class="btn btn-primary rounded-0" type="button" data-toggle="collapse" data-target="#mail" aria-expanded="false" aria-controls="collapseExample">
                Edit
            </button>

        </div>   

        <div class="collapse" id="mail">
            <div class="card rounded-0 card-body">
                <form action="{{ route('editProfile') }}" method="get">
                    @csrf
                    <div class="form-group">
                        <input type="text" class="form-control" name="useremail" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="{{ $user->email }}">
                    </div>
                
                    <button type="submit" class="btn btn-primary rounded-0">Change</button>
                </form>
            </div>
        </div>
    </div>
    <!--  -->
    <div>
        <div class="d-flex justify-content-between py-2 align-items-center border-bottom border-top">

            <h6 class="text-muted f-16"><span class="mr-2" style="font-size: 25px;">📞</span> Contact</h6>

            <button class="btn btn-primary rounded-0" type="button" data-toggle="collapse" data-target="#Contact" aria-expanded="false" aria-controls="collapseExample">
                Edit
            </button>

        </div>   

        <div class="collapse" id="Contact">
            <div class="card rounded-0 card-body">
                <form action="{{ route('editProfile') }}" method="get">
                    @csrf
                    <div class="form-group">
                        <input type="text" class="form-control" name="usercontact" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="@if($user->contact != '') {{ $user->contact }}  @else No Number found   @endif">
                    </div>
                
                    <button type="submit" class="btn btn-primary rounded-0">Change</button>
                </form>
            </div>
        </div>
    </div>
    <!-- description -->
    <div>
        <div class="d-flex justify-content-between py-2 align-items-center border-bottom border-top">

            <h6 class="text-muted f-16"><span class="mr-2" style="font-size: 25px;">⌨️</span> Description</h6>

            <button class="btn btn-primary rounded-0" type="button" data-toggle="collapse" data-target="#desc" aria-expanded="false" aria-controls="collapseExample">
                Edit
            </button>

        </div>   

        <div class="collapse" id="desc">
            <div class="card rounded-0 card-body">
                <form action="{{ route('editProfile') }}" method="get">
                    @csrf
                    <div class="form-group">
                        <textarea class="form-control" name="userdesc" id="exampleFormControlTextarea1" rows="3" placeholder="@if($user->contact != '') {{ $user->description }}  @else Hey can I have Your Intro  @endif"></textarea>
                    </div>
                
                    <button type="submit" class="btn btn-primary rounded-0">Change</button>
                </form>
            </div>
        </div>
    </div>
    <!--  -->

</div>
@endsection



@section('script')

<script>

$(document).ready(function(){
   
    $("form").submit(function(e) {

        e.preventDefault(); // avoid to execute the actual submit of the form.

        var form = $(this);
        var url = form.attr('action');
        console.log(form.attr('action'));
        console.log(form.serialize());
        $.ajax({
            type: "get",
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