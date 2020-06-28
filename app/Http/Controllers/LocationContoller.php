<?php

namespace App\Http\Controllers;

use Illuminate\{
    Http\Request,
    Support\Facades\DB,
    Support\Facades\Auth,
};


class LocationContoller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user.location');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $au_locations = DB::table('location_tags')
        ->leftjoin('locations', 'locations.id', '=', 'location_tags.location_id')
        ->where('location_tags.author_id','=', Auth::id())
        ->where('location_tags.deleted', 0)
        ->get();

        return view('user.uploadlocation',['au_locations' => $au_locations]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

            if ($request->has('location') && isset($request->location)) {
                

                $locations = explode(',', $request->input('location'));        

                foreach($locations as $_location){  
                
                    $location = DB::table('locations')->where('location', 'like', $_location)->first();

                    if($location){   

                        $id_loc = $location->id;

                        $validate = DB::table('location_tags')
                        ->where('author_id',  Auth::id())
                        ->where('location_id', $id_loc)
                        ->first();

                        if (!isset($validate)) {

                            DB::table('location_tags')->insert(
                                ['author_id' => Auth::id(), 'location_id' => $id_loc]
                            );

                        }else{

                            if ($validate->deleted){

                                DB::table('location_tags')
                                ->where('id', $validate->id )
                                ->update(['deleted'=>0]);

                                return back()->with('status', 'Location Added 😊');

                            }else{
                                
                                return back()->with('status', 'Location Already Exist 😍');

                            }
                        }

                       

                    }else{

                        $id_loc = DB::table('locations')->insertGetId([
                            'location' => $_location
                        ]);  

                        DB::table('location_tags')->insert(
                            ['author_id' => Auth::id(), 'location_id' => $id_loc]
                        );


                    }

                }   


            }
          

            DB::commit();
           
            return back()->with('status', 'Location Added 😊');


        }catch (Exception $e) {
           
            DB::rollback();
            
            return back()->with('status', 'Failed To ADD Location 😕 ');
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
        DB::table('location_tags')
        ->where('author_id', Auth::id() )
        ->where('location_id', $id )
        ->update(['deleted' => 1]);

        return back()->with('status', 'Location Removed 😅');

    }
}
