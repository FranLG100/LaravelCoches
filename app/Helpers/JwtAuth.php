<?php
namespace App\Helpers;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use App\User;

class JwtAuth{

    public $key;

    //Nuestra clave secreta
    public function __construct(){
        $this->key='C@ches44';
    }

    /**
     * Método para logearnos en la aplicación
     * @param string $email Email del usuario que entra
     * @param string $password Password del usuario que entra
     * @param boolean $getToken Si es true, devuelve el token decodificado, si no, el token en sí
     * @return mixed Puede devolver el token, el token decodificado, o una respuesta de error
     */
    public function signup($email, $password, $getToken=null){

        //Buscamos en la tabla personas al primero que reuna los requisitos de 
        //email y password
        $user=User::where(
            array(
                'email'=>$email,
                'password'=>$password
            )
        )->first();

        //Si lo encuentra, ponemos a true $signup, es decir, existe ese usuario
        //asociado a ese password
        $signup=false;
        if(is_object($user)){
            $signup=true;
        }

        //Si consigue entrar, formamos un token con la informacion necesaria
        if($signup){

            //$fam=DB::table('personas')->select('*')
            //->where('DNI', $user->ID)->first();

            $token = array(
                'sub'=>$user->id,
                'email' => $user->email,
                'name' => $user->name,
                'surname' => $user->surname,
                'iat' => time(),
                'exp' => time()+(7*24*60*60)
            );

            //formamos el token, y una versión decodificada del mismo
            $jwt=JWT::encode($token,$this->key,'HS256');
            $decoded=JWT::decode($jwt,$this->key,array('HS256'));

            //Si la variable $getToken no es true, enviamos el token
            if(is_null($getToken)){
                return $jwt;
            }else{
                return $decoded; //Si sí lo es, enviamos la informacion decodificada
            }

            //Si no ha conseguido logearse, enviamos un mensaje de error
        }else{
            return array('status'=>'error',
            'message'=>'Login fallido');
        }
    }

    /**  
     * @param string $jwt Token
     * @param boolean $getIdentity Para saber si queremos o no los datos del token
     * @return mixed $auth Si el token se ha verificado o no correctamente (boolean) o el token decodificado
    */
    public function checkToken($jwt,$getIdentity=false){
        $auth=false;

        //Decodificamos el token
        try{
            $decoded=JWT::decode($jwt,$this->key,array('HS256'));

        }catch(\UnexpectedValueException $e){
            $auth=false;
        }catch(\DomainException $e){
            $auth=false;
        }

        //Si el token está bien formado, y posee un rasgo idetificativo (sub, el dni en este
        //caso particular), devolvemos true
        if(isset($decoded) && is_object($decoded) && isset($decoded->sub)){
            $auth=true;
        }else{
            $auth=false; //En caso contrario, devolvemos false
        }

        //Si $getIdentity es verdadero, devolvemos la información que contiene el token
        if($getIdentity){
            return $decoded;
        }

        //Si no, solamente verdadero o falso
        return $auth;
    }


}