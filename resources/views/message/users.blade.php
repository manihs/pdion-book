<div class="userlist bg-white" style="min-height:100vh;">
        @foreach ($users as $user)
                <!--  -->
                <a href="{{ route('messagebox',['sender'=> $user->id,'reciever'=> Auth::user()->id]) }}" class="text-decoration-none" data-uuid="{{ $user->id }}">
                        <div class="row user mx-auto py-3 border-bottom" style="min-width: 200px;">
                                <div class="col-2 p-1">
                                <div class="rounded-circle overflow-hidden mx-auto" style="height: 50px; width: 50px;">
                                        <img src="{{ $user->image }}" alt="" height="50px" width="50px" >
                                </div>
                                </div>
                                <div class="col-8 d-jc">
                                <div class="messages-name">{{ $user->name }}</div>
                                <div class="messages-sub  text-muted" style="font-size:14px;">
                                        <p class="show m-0 text-message">{{ $user->message }} </p>
                                        <p class="show-typing m-0 text-success" style="display:none;">typing</p>
                                </div>
                                </div>
                                <div class="col-2 p-1 text-center seen d-jc">
                                @if( $user->unread )
                                        <div class="messages-time text-info mb-1" style="font-size:10px;">{{ $user->time }}</div>
                                        <div class="text-white">
                                                <div class="rounded-pill badge p-1 px-2" style="background: green; font-size:10px; display: inline-block;">
                                                        {{ $user->unread }} 
                                                </div>
                                        </div>
                                @else
                                        <div class="messages-time text-info mb-1" style="font-size:10px;">{{ $user->time }}</div>
                                        <div class="text-white invisible">
                                                <div class="rounded-pill badge p-1 px-2" style="background: green; font-size:10px; display: inline-block;">
                                                        {{ $user->unread }} 
                                                </div>
                                        </div>

                                @endif
                                </div>
                        </div>
                </a>
                <!--  -->
        @endforeach
</div>
