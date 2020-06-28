@foreach($posts as $post)
<div class="modal fade " id="message-{{ $post->postid }}" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="message-{{ $post->postid }}" aria-hidden="true">
  <div class="modal-dialog modal-message">
    <div class="modal-content rounded-0">
      <div class="modal-header border-0">
        <h5 class="modal-title" id="message-{{ $post->postid }}">Send Message To {{ $post->username }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id=>
      <form action="{{ route('sendmessage') }}" class="ajax-message" method="post">
        @csrf
        <input class="form-control mb-1" type="hidden" name="to" value="{{ $post->userid  }}" >
        <input class="form-control mb-1" type="hidden" name="product" value="{{ $post->title }}" >
        <textarea  class="form-control mb-3" rows="3" type="text" name="message" placeholder="Write message" value="
        hey {{ $post->title }} Is still available ?
        "></textarea>
        <button type="submit" class="btn btn-success send rounded-0">Send</button>
      </form>
      </div>
    </div>
  </div>
</div>

<div class="row mx-auto my-2 py-3 border-top bg-white border">
    <div class="col-4">
        <img src="https://picsum.photos/200/300?nocache=<?php echo microtime(); ?>" alt="" width="100%">
    </div>
    <div class="col-8 d-flex flex-column justify-content-between">
        <div class="">
          <h4 class="" id="" style="font-size: 16px;">{{ $post->title }}</h4>
          <p class="text-muted mb-2" style="font-size: 12px;">{{ $post->subtitle }}</p>
          <a class="badge badge-primary text-wrap mb-3 p-2" data-toggle="modal" data-target="#detail-{{ $post->postid }}">
            <img src="https://img.icons8.com/ios/50/ffffff/view-file.png" width="30px"/>
          </a>
        </div>
        <div class="d-flex justify-content-between align-items-end">
            <div class="" id="">
                <p class="mb-0 " style="font-size: 18px;">
    
    
                <span class="price-tag">Rs .</span><span class="price font-weight-bold">{{ $post->price }}</span>   
                </p>
            </div>
            <div class="d-flex align-items-end ">
            @guest
                  <button class="btn btn-primary rounded-0 " data-toggle="modal" data-target="#message-{{ $post->postid }}">Message</button>
            @endguest
              @auth
                @if(Auth::user()->id == $post->userid )
                  <a href="" class="btn btn-primary rounded-0 ">Edit</a>
                  <a href="{{ route('bookDelete', ['id' => $post->postid]) }}" class="btn btn-danger rounded-0 ">Delete</a>
                @else
                  <button class="btn btn-primary rounded-0 " data-toggle="modal" data-target="#message-{{ $post->postid }}">Message</button>
                @endif
              @endauth
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="detail-{{ $post->postid }}" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="detail-{{ $post->postid }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable mt-5">
    <div class="modal-content detail-model rounded-0">
      <div class="modal-header border-0">
        <h5 class="modal-title model-bookname" id="detail-{{ $post->postid }}">Book detail</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p class="row"><span class="text-info col-3 text-capitalize">title</span><span class="text-muted col-9 text-capitalize">{{ $post->title }}</span></p>
        <p class="row"><span class="text-info col-3 text-capitalize">subtitle</span><span class="text-muted col-9 text-capitalize">{{ $post->subtitle }}</span></p>
        <p class="row"><span class="text-info col-3 text-capitalize">price</span><span class="text-muted col-9 text-capitalize">{{ $post->price }}</span></p>
        <p class="row"><span class="text-info col-3 text-capitalize">Author</span><span class="text-muted col-9 text-capitalize">{{ $post->author }}</span></p>
        <p class="row"><span class="text-info col-3 text-capitalize">Publisher</span><span class="text-muted col-9 text-capitalize">{{ $post->publisher }}</span></p>
        <p class="row"><span class="text-info col-3 text-capitalize">location</span><span class="text-muted col-9 text-capitalize">{{ $post-> location }}</span></p>
        <p class="row"><span class="text-info col-3 text-capitalize">language</span><span class="text-muted col-9 text-capitalize">{{ $post->language }}</span></p>
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
       
      </div>
    </div>
  </div>
</div>
@endforeach