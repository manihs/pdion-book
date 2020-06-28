@php ($new = 1)

@foreach ($messages as $message)

    @if($message->unseen == 1 && $new == 1 && $message->sender != Auth::user()->id)

        @php ($new = 0) 

        <div class="text-center text-muted"> ------------------- New Message ------------------- </div>

    @endif

    @if($message->sender == Auth::user()->id )

        <div class="d-flex justify-content-end my-2 user-message" data-track="{{ $message->id }}">
            <div class="d-flex flex-column align-items-end">
                <div class="d-flex">
                    <div href="#" class="btn btn-primary rounded-pill px-4"> 
                        {{ $message->text }}
                    </div>
                    <img src="{{ Auth::user()->image }}" alt="" srcset="" class="rounded-circle mx-2" width="30px" height="30px">
                </div>
                <span class="text-muted my-1" style="font-size: 9px;"> 
                {{ date("d F Y g:i A", strtotime($message->created_at.' + 5 hours 29 min')) }}
                </span>
            </div>
        </div>
        
    @else

        <div class="d-flex justify-content-start my-2 user-message" data-track="{{ $message->id }}">
            <div class="d-flex flex-column align-items-start">
                <div class="d-flex">
                    <img src="{{ $user->image }}" alt="" srcset="" class="rounded-circle mx-2" width="30px" height="30px">
                    <div href="#" class="btn btn-primary rounded-pill px-4"> 
                        {{ $message->text }}
                    </div>
                </div>
                <span class="text-muted my-1" style="font-size: 9px;"> 
                {{ date("d F Y g:i A", strtotime($message->created_at.' + 5 hours 29 min')) }}
                </span>
            </div>
        </div>

    @endif

@endforeach         
    
