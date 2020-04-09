<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\JwtAuth;
use App\Car;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $hash=$request->header('token');
        $jwtAuth=new JwtAuth();
        $checkToken=$jwtAuth->checkToken($hash);

        if($checkToken){
            echo 'Funciono';
        }else{
            echo 'No autenticado';
        }
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $hash=$request->header('token');
        $jwtAuth=new JwtAuth();
        $checkToken=$jwtAuth->checkToken($hash);

        if($checkToken){
            $json=$request->input('json',null);
            $params = json_decode($json);
            $params_array=json_decode($json,true);

            $user=$jwtAuth->checkToken($hash,true);

            $validate=\Validator::make($params_array,[
                'title'=>'required|min:5',
                'description'=>'required',
                'price'=>'required',
                'status'=>'required'
            ]);

            if($validate->fails()){
                return response()->json($validate->errors(),400);
            }

            $car=new Car();
            $car->user_id=$user->sub;
            $car->title=$params->title;
            $car->description=$params->description;
            $car->price=$params->price;
            $car->status=$params->status;
            $car->save();
            //$email = (!is_null($json) && isset($params->email)) ? $params->email:null;
        
            $data=array(
                'status'=>'success',
                'code'=>200,
                'message'=>'Coche insertado con exito'
            );
            return response()->json($data,200);
        }else{
            $data=array(
                'status'=>'error',
                'code'=>500,
                'message'=>'Usuario inexistente'
            );
            return response()->json($data,500);
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
        //
    }
}
