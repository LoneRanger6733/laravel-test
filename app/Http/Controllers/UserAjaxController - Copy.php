<?php

         

namespace App\Http\Controllers;

          

use App\User;

use Illuminate\Http\Request;

use DataTables;

use Illuminate\Support\Str; 
        

class UserAjaxController extends Controller

{  



    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function index(Request $request)

    {

   

        if ($request->ajax()) {

            $data = User::latest()->get();

            return Datatables::of($data)

                    ->addIndexColumn()

                    ->filter(function ($instance) use ($request) {

                        if (!empty($request->get('email'))) {

                            $instance->collection = $instance->collection->filter(function ($row) use ($request) {

                                return Str::contains($row['email'], $request->get('email')) ? true : false;

                            });

                        }

   

                        if (!empty($request->get('search'))) {

                            $instance->collection = $instance->collection->filter(function ($row) use ($request) {

                                if (Str::contains(Str::lower($row['email']), Str::lower($request->get('search')))){

                                    return true;

                                }else if (Str::contains(Str::lower($row['first_name']), Str::lower($request->get('search')))) {

                                    return true;

                                }

   

                                return false;

                            });

                        }

   

                    })

                    ->addColumn('action', function($row){

   

                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm edituser">Edit</a>';

   

                           $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteuser">Delete</a>';

    

                            return $btn;

                    })

                    ->rawColumns(['action'])

                    ->make(true);

        }

      

        return view('UserAjax',compact('User'));

    }

     

    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */

    public function store(Request $request)

    {

        User::updateOrCreate(['id' => $request->user_id],

                ['email' => $request->email, 
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'status' => $request->status
            ]
            );        

   

        return response()->json(['success'=>'User saved successfully.']);

    }

    /**

     * Show the form for editing the specified resource.

     *

     * @param  \App\user  $user

     * @return \Illuminate\Http\Response

     */

    public function edit($id)

    {

        $user = User::find($id);

        return response()->json($user);

    }

  

    /**

     * Remove the specified resource from storage.

     *

     * @param  \App\user  $user

     * @return \Illuminate\Http\Response

     */

    public function destroy($id)

    {

        User::find($id)->delete();

     

        return response()->json(['success'=>'User deleted successfully.']);

    }

}