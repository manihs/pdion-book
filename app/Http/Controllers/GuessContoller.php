<?php

namespace App\Http\Controllers;

use Illuminate\{
    Http\Request,
    Support\Facades\DB,
    Support\Facades\Auth,
    Support\Str,
};

class GuessContoller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request  $request)
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
        ->leftJoin('locations', 'locations.id', '=', 'location_tags.location_id');
      

        if ($request->has('location') && isset($request->location) && $request->get('location') !== null) {

            $locations =  explode(',', $request->get('location'));
            $posts = $posts->where(function ($posts) use($locations) {

                foreach ($locations as $location){

                    $posts = $posts->orwhere('locations.location', 'Like' ,'%'.$location.'%');
    
                }
    
            });      
            
            $posts = $posts->where('location_tags.deleted', 0);

        }
        $posts = $posts->leftJoin('users', 'users.id', '=', 'posts.author_id');

        if ($request->has('query') && isset($request->query) && $request->get('query') !== null) {

            $posts = $posts->where('posts.post_title','Like', '%'.$request->get('query').'%')
            ->orWhere('posts.post_subtitle','Like', '%'.$request->get('query').'%')
            ->orWhere('apub.arribute_value','Like', '%'.$request->get('query').'%')
            ->orWhere('aauth.arribute_value','Like', '%'.$request->get('query').'%');

        }
              
        $posts = $posts  ->where('location_tags.deleted', 0)
                         ->where('posts.deleted', 0)

        
        ->groupBy('posts.id','posts.post_title','posts.post_subtitle','posts.post_name','posts.post_picture')
                        ->groupBy('post_metas.maxprice')
                        ->groupBy('users.name','users.id');

        $posts = $posts->paginate(3);


        if ($request->ajax()) {
            
            $view = view('post',compact('posts'))->render();

            if(!empty($view)){

                return response()->json(['html'=>$view]);

            }
            
            return true;
        }

        return view('home',['posts' => $posts,'showFilter'=> true,'location'=> $request->get('location'), 'query'=> $request->get('query')]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    function searchSuggestion(Request  $request){

        $posts = DB::table('posts')
        ->select(DB::raw("posts.post_title as value"))
                ->where('posts.post_title','Like', '%'.$request->input('str').'%')
                ->limit(5)
                ->get();

        if ($request->ajax()) {

            $data = '';
            foreach($posts as $posts){
                $data .= '<li class="list-group-item">'.$posts->value.'</li>';
            }

            return $data;

        }

        return true;

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
}
