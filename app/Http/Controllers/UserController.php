<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\User;
use App\Helpers\JwtAuth;

class UserController extends Controller
{

    public function registro(Request $request){

        $json = $request->input('json',null);
        $params = json_decode($json);

        $email= (!is_null($json) && isset($params->email)) ? $params->email : null;
        $name= (!is_null($json) && isset($params->name)) ? $params->name : null;
        $surname= (!is_null($json) && isset($params->surname)) ? $params->surname : null;
        $role= 'ROLE_USER';
        $password= (!is_null($json) && isset($params->password)) ? $params->password : null;

        //Comienzo del proceso de guardado
        if(!is_null($email) && !is_null($password)){
            $user=new User();
            $user->email=$email;
            $user->name=$name;
            $user->surname=$surname;
            $user->role=$role;
            $pwd=hash('sha256',$password);
            
            //Comprobar si existe el usuario
            $isset_user=DB::table('users')->where('email',$email)->count();

            //Si ese usuario no existe, se crea
            if($isset_user==0){
                
                $user->password=$pwd;
                $user->save();

                $data=array(
                    'status'=>'success',
                    'code'=>200,
                    'message'=>'Usuario registrado con exito'
                );
            }else{
                $data=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'Usuario duplicado'
                );
            }
        }else{
            $data=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'Usuario no creado'
            );
        }

        return response()->json($data,200);
    }

    /**
     * Método para logearse en la aplicación
     */
    public function login(Request $request){

        $jwtAuth=new JwtAuth();

        $json = $request->input('json',null);
        $params = json_decode($json);

        $email = (!is_null($json) && isset($params->email)) ? $params->email:null;
        $password = (!is_null($json) && isset($params->password)) ? $params->password:null;
        $getToken = (!is_null($json) && isset($params->gettoken)) ? $params->gettoken:null;
    
        //Cifrar password
        $pwd=hash('sha256',$password);

        if(!is_null($email) && !is_null($password) && ($getToken==null || $getToken=='false')){
            $signup = $jwtAuth->signup($email,$pwd);
        }elseif($getToken != null){
            $signup=$jwtAuth->signup($email,$pwd,$getToken);
        }else{
            $signup=array(
                'status'=>'error',
                'message'=>'Envia tus datos por post'
            );

        }

        return response()->json($signup,200);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
