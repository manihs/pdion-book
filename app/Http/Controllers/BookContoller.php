<?php

namespace App\Http\Controllers;


use Illuminate\{
    Http\Request,
    Support\Facades\DB,
    Support\Facades\Auth,
    Support\Facades\Redirect,
    Support\Str,
};

class BookContoller extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $post = DB::table('posts')
                ->select(DB::raw("
                                posts.id as postid, 
                                posts.post_title as title,
                                posts.post_subtitle as subtitle,
                                posts.post_name as name,
                                post_metas.maxprice as price,
                                GROUP_CONCAT(DISTINCT  alang.arribute_value , '') as language, 
                                GROUP_CONCAT(DISTINCT  aAuth.arribute_value , '') as Author,
                                GROUP_CONCAT(DISTINCT  aPub.arribute_value , '') as Publisher"
                                ))
                ->leftjoin('post_metas', 'post_metas.post_id','=','posts.id')
            
                ->leftjoin('post_attributes as alang','alang.post_id', '=', 'posts.id')
                ->leftjoin('attributes as lang','lang.id', '=', 'alang.atrribute_id')
                ->where('lang.attribute_label','Like', '%Language%')

                ->leftjoin('post_attributes as aPub','aPub.post_id', '=', 'posts.id')
                ->leftjoin('attributes as pub','pub.id', '=', 'aPub.atrribute_id')
                ->where('pub.attribute_label','Like', '%Publisher%')

                ->leftjoin('post_attributes as aAuth','aAuth.post_id', '=', 'posts.id')
                ->leftjoin('attributes as auth','auth.id', '=', 'aAuth.atrribute_id')
                ->where('auth.attribute_label','Like', '%Author%')

                ->where('posts.author_id', Auth::id())
                ->groupBy('posts.id','posts.post_title','posts.post_subtitle','posts.post_name')
                ->groupBy('post_metas.maxprice')
               
                ->get();
    
                dd($post);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user.uploadbook');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        $title = $subtitle = $price = $path = '';
       

        try {
        
        //---------------------------------------------------------------- value empty or not
        
            if ($request->has('title') && isset($request->title)) {   // title
                            
                $title =   Str::title($request->input('title'));

            }

            if ($request->has('subtitle') && isset($request->subtitle)) {   // subtitle
                            
                $subtitle =   Str::title($request->input('subtitle'));

            }
           
            if ($request->has('price') && isset($request->price)) {   // price
                            
                $price =   $request->input('price');

            }

           
        //----------------------------------------------------------------------- Image   

            if ($request->has('image') && isset($request->image)) {

                $image = $request->file('image');

                $destinationPath = 'users\u-'.Auth::id().'\upload\d-'.date("Y-m-d");

                $path = $destinationPath.'\img-'.auth::id().time().'.'.$image->getClientOriginalExtension();

            }


        //----------------------------------------------------------------------- Post   

            $post_id = DB::table('posts')->insertGetId(

                [
                    
                    'author_id' => Auth::id(),
                    
                    'post_title' => $title,
                    
                    'post_subtitle' => $subtitle,
                    
                    'post_name' => $title,
                   
                    'post_picture' => $path
                
                ]

            );     

        //----------------------------------------------------------------------  post meta 

            DB::table('post_metas')->insert(

                [
                    'post_id' => $post_id, 

                    'maxprice' => $price

                ]
            );

        //----------------------------------------------------------------------  attribute author  

        if ($request->has('author') && isset($request->author)) {
                
            $authors = explode(',', $request->input('author'));

            foreach($authors as $author){                  
                
                $authorAttribute = DB::table('attributes')->where('attribute_label', 'like', '%Author%')->first();

                if ($authorAttribute) {

                    DB::table('post_attributes')->insert(

                        [
                            'post_id' => $post_id, 
        
                            'atrribute_id' => $authorAttribute->id,
                        
                            'arribute_value' => $author
        
                        ]
                    );

                }else{


                    $authorAttributeId = DB::table('attributes')->insertGetId(

                        [
                            
                            'attribute_name' => Str::title('Author'),

                            'attribute_label' => Str::title('Author')
                                              
                        ]
        
                    );     

                       
                    DB::table('post_attributes')->insert(

                        [
                            'post_id' => $post_id, 
        
                            'atrribute_id' => $authorAttributeId,
                        
                            'arribute_value' => $author
        
                        ]

                    );


                }
            }

        }else{

            $authorAttribute = DB::table('attributes')->where('attribute_label', 'like', '%Author%')->first();

            if ($authorAttribute) {

                DB::table('post_attributes')->insert(

                    [
                        'post_id' => $post_id, 
    
                        'atrribute_id' => $authorAttribute->id
    
                    ]
                );

            }else{


                $authorAttributeId = DB::table('attributes')->insertGetId(

                    [
                        
                        'attribute_name' => Str::title('Author'),

                        'attribute_label' => Str::title('Author')
                                          
                    ]
    
                );     

                   
                DB::table('post_attributes')->insert(

                    [
                        'post_id' => $post_id, 
    
                        'atrribute_id' => $authorAttributeId,
                    
                      
    
                    ]

                );


            }

        }

        //----------------------------------------------------------------------  attribute publisher

        if ($request->has('publisher') && isset($request->publisher)) {
                
            $publishers = explode(',', $request->input('publisher'));

            foreach($publishers as $publisher){                  

                $publisherAttribute = DB::table('attributes')->where('attribute_label', 'like', '%Publisher%')->first();

           
                if ($publisherAttribute) {

                    DB::table('post_attributes')->insert(

                        [
                            'post_id' => $post_id, 
        
                            'atrribute_id' => $publisherAttribute->id,
                        
                            'arribute_value' => $publisher
        
                        ]
                    );

                }else{


                    $publisherAttributeId = DB::table('attributes')->insertGetId(

                        [
                            
                            'attribute_name' => Str::title('Publisher'),

                            'attribute_label' => Str::title('Publisher')
                                              
                        ]
        
                    );     

                       
                    DB::table('post_attributes')->insert(

                        [

                            'post_id' => $post_id, 
        
                            'atrribute_id' => $publisherAttributeId,
                        
                            'arribute_value' => $publisher
        
                        ]

                    );


                }
            }

        }else{

            
            $publisherAttribute = DB::table('attributes')->where('attribute_label', 'like', '%Publisher%')->first();

           
            if ($publisherAttribute) {

                DB::table('post_attributes')->insert(

                    [
                        'post_id' => $post_id, 
    
                        'atrribute_id' => $publisherAttribute->id,
                    
                      
    
                    ]
                );

            }else{


                $publisherAttributeId = DB::table('attributes')->insertGetId(

                    [
                        
                        'attribute_name' => Str::title('Publisher'),

                        'attribute_label' => Str::title('Publisher')
                                          
                    ]
    
                );     

                   
                DB::table('post_attributes')->insert(

                    [

                        'post_id' => $post_id, 
    
                        'atrribute_id' => $publisherAttributeId,
                    
                       
    
                    ]

                );


            }

        }

        //----------------------------------------------------------------------  attribute language

        if ($request->has('language') && isset($request->language)) {
                
            $languages = explode(',', $request->input('language'));

            foreach($languages as $language){                  

                $languageAttribute = DB::table('attributes')->where('attribute_label', 'like', '%Language%')->first();
            
                if ($languageAttribute) {

                    DB::table('post_attributes')->insert(

                        [
                            'post_id' => $post_id, 
        
                            'atrribute_id' => $languageAttribute->id,
                        
                            'arribute_value' => $language
        
                        ]
                    );

                }else{


                    $languageAttributeId = DB::table('attributes')->insertGetId(

                        [
                            
                            'attribute_name'  => Str::title('Language'),

                            'attribute_label' => Str::title('Language')
                                              
                        ]
        
                    );     

                       
                    DB::table('post_attributes')->insert(

                        [

                            'post_id' => $post_id, 
        
                            'atrribute_id' => $languageAttributeId,
                        
                            'arribute_value' => $language
        
                        ]

                    );
                }
            }

        }else{

            $languageAttribute = DB::table('attributes')->where('attribute_label', 'like', '%Language%')->first();
            
            if ($languageAttribute) {

                DB::table('post_attributes')->insert(

                    [
                        'post_id' => $post_id, 
    
                        'atrribute_id' => $languageAttribute->id
    
                    ]
                );

            }else{


                $languageAttributeId = DB::table('attributes')->insertGetId(

                    [
                        
                        'attribute_name'  => Str::title('Language'),

                        'attribute_label' => Str::title('Language')
                                          
                    ]
    
                );     

                   
                DB::table('post_attributes')->insert(

                    [

                        'post_id' => $post_id, 
    
                        'atrribute_id' => $languageAttributeId
                    ]

                );
            }
        }

        //----------------------------------------------------------------------  attribute ISBN

        if ($request->has('isbn') && isset($request->isbn)) {
                
            $isbns = explode(',', $request->input('isbn'));


            foreach($isbns as $isbn){               
                
                $isbnAttribute = DB::table('attributes')->where('attribute_label', 'like', '%ISBN%')->first();
                       
                if ($isbnAttribute) {

                    DB::table('post_attributes')->insert(

                        [
                            'post_id' => $post_id, 
        
                            'atrribute_id' => $isbnAttribute->id,
                        
                            'arribute_value' => $isbn
        
                        ]
                    );

                }else{

                    $isbnAttributeId = DB::table('attributes')->insertGetId(

                        [
                            
                            'attribute_name' => 'ISBN',

                            'attribute_label' => 'ISBN'
                                              
                        ]
        
                    );     

                       
                    DB::table('post_attributes')->insert(

                        [

                            'post_id' => $post_id, 
        
                            'atrribute_id' => $isbnAttributeId,
                        
                            'arribute_value' => $isbn
        
                        ]

                    );


                }
            }

        }else{

            $isbnAttribute = DB::table('attributes')->where('attribute_label', 'like', '%ISBN%')->first();
                       
            if ($isbnAttribute) {

                DB::table('post_attributes')->insert(

                    [
                        'post_id' => $post_id, 
    
                        'atrribute_id' => $isbnAttribute->id,
    
                    ]
                );

            }else{


                $isbnAttributeId = DB::table('attributes')->insertGetId(

                    [
                        
                        'attribute_name' => 'ISBN',

                        'attribute_label' => 'ISBN'
                                          
                    ]
    
                );     

                   
                DB::table('post_attributes')->insert(

                    [

                        'post_id' => $post_id, 
    
                        'atrribute_id' => $isbnAttributeId
    
                    ]

                );
            }
        }

        //----------------------------------------------------------------------   upload Image

        if ($request->has('image') && isset($request->image)) {
                
            if($image->move($destinationPath,'img-'.auth::id().time().'.'.$image->getClientOriginalExtension())){

                DB::commit();

            }

        }

        return Redirect::route('home')->with('status', 'Book Have been Added success Fully');

        DB::commit();

        }catch (Exception $e) {
           
            DB::rollback();

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        
       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = DB::table('posts')
                ->select(DB::raw("
                                posts.id as postid, 
                                posts.post_title as title,
                                posts.post_subtitle as subtitle,
                                posts.post_name as name,
                                post_metas.maxprice as price,
                                GROUP_CONCAT(DISTINCT  alang.arribute_value , '') as language, 
                                GROUP_CONCAT(DISTINCT  aAuth.arribute_value , '') as Author,
                                GROUP_CONCAT(DISTINCT  aPub.arribute_value , '') as Publisher"
                                ))
                ->leftjoin('post_metas', 'post_metas.post_id','=','posts.id')
            
                ->leftjoin('post_attributes as alang','alang.post_id', '=', 'posts.id')
                ->leftjoin('attributes as lang','lang.id', '=', 'alang.atrribute_id')
                ->where('lang.attribute_label','Like', '%Language%')

                ->leftjoin('post_attributes as aPub','aPub.post_id', '=', 'posts.id')
                ->leftjoin('attributes as pub','pub.id', '=', 'aPub.atrribute_id')
                ->where('pub.attribute_label','Like', '%Publisher%')

                ->leftjoin('post_attributes as aAuth','aAuth.post_id', '=', 'posts.id')
                ->leftjoin('attributes as auth','auth.id', '=', 'aAuth.atrribute_id')
                ->where('auth.attribute_label','Like', '%Author%')

                ->where('posts.author_id', Auth::id())
                ->where('posts.id', $id)
                ->groupBy('posts.id','posts.post_title','posts.post_subtitle','posts.post_name')
                ->groupBy('post_metas.maxprice')
                ->first();

        return view('user.editbook',['post' => $post]);
       
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
        try {
            DB::beginTransaction();

            DB::commit();
           
         


        }catch (Exception $e) {
           
            DB::rollback();
            
           
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try {

            if ($request->ajax()) {

                if ($request->has('postid') && isset($request->post)) {
    
                    DB::table('posts')
                    ->where('posts.id', $id)
                    ->where('posts.author_id', Auth::id())
                    ->update(['deleted' => true]);
    
                }

            }else{

                    DB::table('posts')
                    ->where('posts.id', $id)
                    ->where('posts.author_id', Auth::id())
                    ->update(['deleted' => true]);


                    return back()->with('status', 'Book Have been Deleted success Fully');

            }

          
 
        }catch (Exception $e) {
           
            
               
        }
    }
}
