<?php

namespace App\Http\Controllers;

use Illuminate\{
   Http\Request,
   Support\Facades\DB,
   Support\Facades\Auth,
   Support\Str,
};


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function home(Request  $request)
    {
      
      $posts = DB::table('posts')
                ->select(DB::raw("
                                posts.id as postid, 
                                users.name as username,
                                users.id as userid,
                                posts.post_title as title,
                                posts.post_subtitle as subtitle,
                                posts.post_name as name,
                                posts.post_picture as cover,
                                post_metas.maxprice as price,
                                GROUP_CONCAT(DISTINCT  alang.arribute_value , '') as language, 
                                GROUP_CONCAT(DISTINCT  aauth.arribute_value , '') as author,
                                GROUP_CONCAT(DISTINCT  apub.arribute_value , '') as publisher,
                                GROUP_CONCAT(DISTINCT  locations.location , '') as location
                                "))
                                ->leftjoin('post_metas', 'post_metas.post_id','=','posts.id')
                                ->leftjoin('post_attributes as alang','alang.post_id', '=', 'posts.id')
                                ->leftjoin('attributes as lang','lang.id', '=', 'alang.atrribute_id')
                                ->where('lang.attribute_label','Like', '%Language%')
                                ->leftjoin('post_attributes as apub','apub.post_id', '=', 'posts.id')
                                ->leftjoin('attributes as pub','pub.id', '=', 'apub.atrribute_id')
                                ->where('pub.attribute_label','Like', '%Publisher%')
                                ->leftjoin('post_attributes as aauth','aauth.post_id', '=', 'posts.id')
                                ->leftjoin('attributes as auth','auth.id', '=', 'aauth.atrribute_id')
                                ->where('auth.attribute_label','Like', '%Author%')
                                ->leftJoin('location_tags', 'location_tags.author_id', '=', 'posts.author_id')
                                ->leftJoin('locations', 'locations.id', '=', 'location_tags.location_id')
                                ->leftJoin('users', 'users.id', '=', 'posts.author_id')
                                ->Where('posts.author_id','=', Auth::id())   
                                ->where('posts.deleted', 0)        
                                ->where('location_tags.deleted', 0)
                                ->groupBy('posts.id','posts.post_title','posts.post_subtitle','posts.post_name','posts.post_picture')
                                ->groupBy('post_metas.maxprice')
                                ->groupBy('users.name','users.id')
                                ->paginate(3);

      if ($request->ajax()) {
          
          $view = view('post',compact('posts'))->render();

          if(!empty($view)){

              return response()->json(['html'=>$view]);

          }
          
          return true;
      }

      return view('user.home',['posts' => $posts]);

    }


    public function profile(Request  $request)
    {
        $user = DB::table('users')->where('users.id',Auth::id())->first();

        $posts = DB::table('posts')
        
        ->select(DB::raw("
                        posts.id as postid, 
                        users.name as username,
                        users.id as userid,
                        posts.post_title as title,
                        posts.post_subtitle as subtitle,
                        posts.post_name as name,
                        posts.post_picture as cover,
                        post_metas.maxprice as price,
                        GROUP_CONCAT(DISTINCT  alang.arribute_value , '') as language, 
                        GROUP_CONCAT(DISTINCT  aauth.arribute_value , '') as author,
                        GROUP_CONCAT(DISTINCT  apub.arribute_value , '') as publisher,
                        GROUP_CONCAT(DISTINCT  locations.location , '') as location
                        "))
                        ->leftjoin('post_metas', 'post_metas.post_id','=','posts.id')
                        ->leftjoin('post_attributes as alang','alang.post_id', '=', 'posts.id')
                        ->leftjoin('attributes as lang','lang.id', '=', 'alang.atrribute_id')
                        ->where('lang.attribute_label','Like', '%Language%')
                        ->leftjoin('post_attributes as apub','apub.post_id', '=', 'posts.id')
                        ->leftjoin('attributes as pub','pub.id', '=', 'apub.atrribute_id')
                        ->where('pub.attribute_label','Like', '%Publisher%')
                        ->leftjoin('post_attributes as aauth','aauth.post_id', '=', 'posts.id')
                        ->leftjoin('attributes as auth','auth.id', '=', 'aauth.atrribute_id')
                        ->where('auth.attribute_label','Like', '%Author%')
                        ->leftJoin('location_tags', 'location_tags.author_id', '=', 'posts.author_id')
                        ->leftJoin('locations', 'locations.id', '=', 'location_tags.location_id')
                        ->leftJoin('users', 'users.id', '=', 'posts.author_id')
                        ->Where('posts.author_id','=', Auth::id())   
                        ->where('location_tags.deleted', 0)
                        ->where('posts.deleted', 0)        
                        ->groupBy('posts.id','posts.post_title','posts.post_subtitle','posts.post_name','posts.post_picture')
                        ->groupBy('post_metas.maxprice')
                        ->groupBy('users.name','users.id')
                        ->paginate(3);


        if ($request->ajax()) {

        $view = view('post',compact('posts'))->render();

        if(!empty($view)){

            return response()->json(['html'=>$view]);

        }

        return true;
        }


        return view('user.profile',['user' => $user, 'posts' => $posts]);
    }

    public function setting()
    {
        $user = DB::table('users')->where('users.id',Auth::id())->first();
        return view('user.setting',['user' => $user]);
    }

    public function help()
    {
       return view('user.help');
    }

    public function editProfile(Request  $request)
    {

        if ($request->ajax()) {

            if ($request->has('username') && isset($request->username)) {

                DB::table('users')
                ->where('users.id', Auth::id())
                ->update(['name' => $request->username]);

            }

            if ($request->has('useremail') && isset($request->useremail)) {

                DB::table('users')
                ->where('users.id', Auth::id())
                ->update(['email' => $request->useremail]);

            }

            if ($request->has('usercontact') && isset($request->usercontact)) {

                DB::table('users')
                ->where('users.id', Auth::id())
                ->update(['contact' => $request->usercontact]);

            }

            if ($request->has('userdesc') && isset($request->userdesc)) {

                DB::table('users')
                ->where('users.id', Auth::id())
                ->update(['description' => $request->userdesc]);

            }

        }

       return $request->all();
    }

}
