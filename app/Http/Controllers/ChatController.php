<?php

namespace App\Http\Controllers;
use App\Message;
use App\Events\MessageEvent;

use Illuminate\{
    Http\Request,
    Support\Facades\DB,
    Support\Facades\Auth,
    Pagination\LengthAwarePaginator as Paginator
};


class ChatController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function getUser (Request $request) {

        $userid = Auth::id();

        $users = DB::select( DB::raw("
            SELECT * FROM (
                SELECT l.sender, 
                substring_index(group_concat( l.mid order by l.time desc), ',', 1) as mid,  
                l.name, 
                l.id, 
                l.image, 
                l.reciver, 
                l.unread, 
                substring_index(group_concat( l.message order by l.time desc), ',', 1) as message,  
                substring_index(group_concat( l.time order by l.time desc), ',', 1) as time
            
                
                FROM (
                        SELECT  messages.sender as sender,
                                substring_index(group_concat(messages.id order by messages.created_at desc), ',', 1) as mid,
                                users.name,
                                users.id,
                                users.image,
                                messages.reciver as reciver,
                                sum(messages.unseen) as unread,
                                substring_index(group_concat(messages.text order by messages.created_at desc), ',', 1) as message,
                                substring_index(group_concat(messages.created_at order by messages.created_at desc), ',', 1) as time
                            
                        from users
                        left join messages on messages.sender = users.id
                        WHERE messages.reciver = ".$userid."
                        GROUP BY messages.sender
                        UNION
                        SELECT  messages.sender as sender,
                                substring_index(group_concat(messages.id order by messages.created_at desc), ',', 1) as mid,
                                users.name,
                                users.id,
                                users.image,
                                messages.reciver as reciver,
                                NULL as unread,
                                substring_index(group_concat(messages.text order by messages.created_at desc), ',', 1) as message,
                                substring_index(group_concat(messages.created_at order by messages.created_at desc), ',', 1) as time
                        from users
                        left join messages on messages.reciver  = users.id
                        WHERE messages.sender = ".$userid."
                        GROUP BY messages.reciver
                    ) l
                    where l.name is NOT null
                    Group By name
                ) t  ORDER by t.mid DESC
            "));

           
            $total = count($users);
            $per_page = 15;
            $current_page = $request->input("page") ?? 1; 
            $starting_point = ($current_page * $per_page) - $per_page;
            $users = array_slice($users, $starting_point, $per_page, true);
        
            $users = new Paginator($users, $total, $per_page, $current_page, [
                'path' => $request->url(),
                'query' => $request->query(),
            ]);
            
        return view('message.home',['users'=> $users]);

    }

    public function sendMessage(Request $request) {

        if($request->input('to') == Auth::id())  {

            return "Srry Its Seem's Your Sendiing message To your Self";

        }

        $id = DB::table('messages')->insertGetId(
            [
                'reciver' => $request->input('to'), 
                'sender' => Auth::id(),
                'text' => $request->input('message')

            ]
        );

        $message =  DB::table('messages')->where('messages.id',$id)->first();

        $user = DB::table('users')->select('name','id','image')->where('users.id', Auth::id())->first();
        
        broadcast(New MessageEvent($user, $message));

        $message = '
            <div class="d-flex justify-content-end my-2 user-message" data-track="'.$message->id.'">
                <div class="d-flex flex-column align-items-end">
                    <div class="d-flex">
                        <div href="#" class="btn btn-primary rounded-pill px-4"> 
                            '.$message->text.'
                        </div>
                        <img src="{{ Auth::user()->image }}" alt="" srcset="" class="rounded-circle mx-2" width="30px" height="30px">
                    </div>
                    <span class="text-muted my-1" style="font-size: 9px;"> 
                     '.date("d F Y g:i A", strtotime($message->created_at )) .' 
                    </span>
                </div>
            </div>
        ';
        
        return response()->json([ 'data'=> $message]);
    }

    public function messageSeen(Request $request) {

        if ( $request->input('to') == Auth::id() ) {

            DB::table('messages')
            ->where('reciver', Auth::id())
            ->where('sender',  $request->input('sender'))
            ->update(['unseen' => 0]);

        }
       

        return $request->all();

    }

    public function messagecount() {

      

            $count = DB::table('messages')
            ->select(DB::raw('count(distinct sender) as count'))
            ->where('reciver', Auth::id())
            ->where('unseen', DB::raw(1))
            ->groupBy('sender')
            ->get();

            $count = count($count);       

        return  $count;

    }

    public function MessageBox($reciever) {

         
        $track = DB::table('messages')
                ->select(DB::raw(' messages.id as id, sum(messages.unseen) AS counts '))
                ->where( 'messages.reciver', '=', Auth::id() )
                ->where( 'messages.sender', '=', $reciever )
                ->where( 'messages.unseen', '=', 1 )
                ->groupBy( 'messages.sender' )
                ->first();

        if (!is_null($track)){
     
            if ($track->counts < 5 ) {
                
            $messages =  DB::select( DB::raw("  

                    SELECT * 
                    FROM    (
                        SELECT  id, sender, reciver, text, unseen, created_at
                        FROM    `messages` 
                        WHERE   ( messages.sender = ".Auth::id()." or messages.reciver = ".Auth::id()." ) 
                        AND     ( messages.sender = $reciever or messages.reciver = ".$reciever." )
                        ORDER by created_at DESC
                        limit 8
                    ) m
                    ORDER by m.created_at ASC, m.id ASC
            
                "));

            }else{
                
                // load 20 data

                $messages =  DB::select( DB::raw("  

                    SELECT * 
                    FROM    (
                        SELECT  id, sender, reciver, text, unseen, created_at
                        FROM    `messages` 
                        WHERE   ( messages.sender = ".Auth::id()." or messages.reciver = ".Auth::id()." ) 
                        AND     ( messages.sender = $reciever or messages.reciver = ".$reciever." )
                        AND     ( messages.id >=  $track->id AND messages.sender = $id )
                        ORDER by created_at DESC
                    ) m
                    ORDER by m.created_at ASC, m.id ASC
            
                "));
                

                dd($messages);


            }

        }else {

            // no new record

            $messages =  DB::select( DB::raw("  

                SELECT * 
                FROM    (
                    SELECT  id, sender, reciver, text, unseen, created_at
                    FROM    `messages` 
                    WHERE   ( messages.sender = ".Auth::id()." or messages.reciver = ".Auth::id()." ) 
                    AND     ( messages.sender = $reciever or messages.reciver = ".$reciever." )
                    ORDER by created_at DESC
                    limit 8
                ) m
                ORDER by m.created_at ASC, m.id ASC
        
            "));

        }
   
        $user = DB::table( 'users' )->where( 'id', $reciever )->first();

        DB::table('messages')->where('reciver', Auth::id())->where('sender',  $reciever)->update(['unseen' => 0]);

        return view('message.messagebox',['messages'=>$messages,'user'=>$user]);       
        
    }

   public function oldMessage (Request $request) {

            $track = $request->input('track');

            $id =  $request->input('id');

            $user = DB::table( 'users' )->where( 'id', $id )->first();

            $messages =  DB::select( DB::raw("  

                    SELECT * 
                    FROM    (
                        SELECT  id, sender, reciver, text, unseen, created_at
                        FROM    `messages` 
                        WHERE   ( messages.sender = ".Auth::id()." or messages.reciver = ".Auth::id()." ) 
                        AND     ( messages.sender = $id or messages.reciver = $id )
                        AND     ( messages.id < $track )
                        ORDER by created_at DESC
                        limit 5
                    ) m
                    ORDER by m.created_at ASC, m.id ASC

                "));

            $view = view('message.message',compact('messages','user'))->render();

            if(!empty($view)){

                return response()->json(['html'=>$view]);

            }


            return $messages;
            
    }


}
