@extends('layouts.app')

@section('navbar')

@include('component.nav')

@endsection

@section('content')



<div class="p-sm-0 p-md-0 p-lg-0 col-12 col-sm-11 col-md-7 col-lg-6 mx-auto pt-2">

<div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom">
    <h6 class="text-muted m-0">Upload Book</h6>
    <a class="btn btn-danger rounded-0 text-white" href="{{ url('home') }}">Cancel</a>
</div>

<nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <a class="nav-item nav-link active" id="nav-step-1-tab" data-toggle="tab" href="#nav-step-1" role="tab" aria-controls="nav-step-1" aria-selected="true">Step 1</a>
        <a class="nav-item nav-link" id="nav-step-2-tab" data-toggle="tab" href="#nav-step-2" role="tab" aria-controls="nav-step-2" aria-selected="false">Step 2</a>
        <a class="nav-item nav-link" id="nav-step-3-tab" data-toggle="tab" href="#nav-step-3" role="tab" aria-controls="nav-step-3" aria-selected="false">Step 3</a>
        <a class="nav-item nav-link" id="nav-step-4-tab" data-toggle="tab" href="#nav-step-4" role="tab" aria-controls="nav-step-4" aria-selected="false">Step 4</a>
    </div>
</nav>

<form action="{{ route('bookUploadStore') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="tab-content" id="nav-tabContent">

        <div class="jumbotron tab-pane fade show rounded-0 active" id="nav-step-1" role="tabpanel" aria-labelledby="nav-step-1-tab">
            <div class="d-flex justify-content-between flex-column vh-50">
                <div>
                    <p class="text-muted mb-1">Enter ISBN number </p>
                    <div class="form-group">
                        <input type="text" class="form-control rounded-0" placeholder="isbn" name="isbn" required>       
                    </div>

                    <p class="text-muted mb-1">Enter Title (Heading)</p>
                    <div class="form-group">
                        <input type="text" class="form-control rounded-0" placeholder="Title" name="title" required>       
                    </div>

                    <p class="text-muted mb-1">Enter Sub-title (Sub - Heading) optinal </p>
                    <div class="form-group">
                        <input type="text" class="form-control rounded-0" placeholder="Sub Title" name="subtitle">       
                    </div>

                    <p class="text-info">Note</p>
                    <p class="text-muted mb-1">Spelling Need To be checked, It increases probability To find Your Books by others</p>
                </div>

            </div>

        </div>


        <div class="jumbotron tab-pane fade rounded-0" id="nav-step-2" role="tabpanel" aria-labelledby="nav-step-2-tab">
            <div class="d-flex justify-content-between flex-column vh-50">
                <div>

                    <p class="text-muted mb-1">Enter The Author Name</p>
                    
                    <div class="form-group">
                        <input type="text" class="form-control rounded-0" placeholder="author" name="author" required>       
                    </div>

                    <p class="text-muted mb-1">Enter The Publisher</p>

                    <div class="form-group">
                        <input type="text" class="form-control rounded-0" placeholder="publisher" name="publisher" required>       
                    </div>

                    <p class="text-info">Note</p>
                    <p class="text-muted mb-1">Spelling Need To be checked, It increases probability To find Your Books by others</p>
                    <p class="text-muted">If Case to add multiple author comma ( , ) to be used to seprate the Name</p>

                </div>
            </div>
        </div>

        
        <div class="jumbotron tab-pane fade rounded-0" id="nav-step-3" role="tabpanel" aria-labelledby="nav-step-3-tab">
            <div class="d-flex justify-content-between flex-column vh-50">
                <div>
                    <p class="text-muted mb-1">Enter The Price to be displayed </p>
                    <div class="form-group">
                        <input type="number" class="form-control rounded-0" placeholder="price" name="price" required>       
                    </div>

                    <p class="text-muted mb-1">Enter the language the book your holding</p>

                    <div class="form-group">
                        <input type="text" class="form-control rounded-0" placeholder="language ( English, Hindi )" name="language" required>       
                    </div>

                    <p class="text-info">Note</p>
                    <p class="text-muted mb-1">Spelling Need To be checked, It increases probability To find Your Books by others</p>
                    <p class="text-muted">If Case to add multiple language comma ( , ) to be used to seprate the language</p>

                </div>
            </div>
        </div>

        <div class="jumbotron tab-pane fade rounded-0" id="nav-step-4" role="tabpanel" aria-labelledby="nav-step-4-tab">
            <div class="d-flex justify-content-between flex-column vh-50">
                <div>

                    <div class="col-4 m-auto">
                        <div class="mt-2 mb-2">
                            <img id="output-img" width="100%">     
                        </div>
                    </div>

                    <p class="text-muted mb-1">Upload Cover Image</p>
                    <div class="form-group">
                        <input type="file" class="form-control rounded-0" placeholder="cover image" name="image" onchange="loadFile(event)" required>       
                    </div>
                </div>    
               
                <button type="submit" class="btn btn-success rounded-0 btn-mw">Upload</button>
            </div>
        </div>

    </div>
</form>
</div>
      
    
@endsection



@section('script')
<script>
var loadFile = function(event) {
    var reader = new FileReader();
    reader.onload = function(){
      var output = document.getElementById('output-img');
      output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
  };
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
