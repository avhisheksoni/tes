<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //
    public function register(Request $request){
         $validator = Validator::make($request->all(),[
             "name"=>'required|string|min:2|max:100',
             "email"=>'required|string|email|max:100|unique:users',
             'password'=>'required|string|min:6|confirmed'
         ]);
         if( $validator->fails()){
            return response()->json($validator->errors(),400);
         }
         $user = User::create([
            'name'=> $request->name,
            'email'=>$request->email,
            'password'=> Hash::make($request->password)
         ]);
         return response()->json([
            'message'=>'User registerd successfully',
            'user'=>$user
         ]);

    }

    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            "email"=>'required|string|email',
            'password'=>'required|string|min:6'
        ]);
        if( $validator->fails()){
           return response()->json($validator->errors(),400);
        }

        if(!$data = auth()->attempt($validator->validate())){
            return response()->json([
             'error'=>'Unautorize',
            ]);
        }else{
            return response()->json([
                'message'=>'Success',
               ]);
        }

    }

    public function user(){
         $user = User::get();
        return response()->json(['status'=>200,'data'=>$user,'message'=>'User found!']);
    }

    public function userinfo(Request $request ,$id){
        
        try{
        $user = User::find($id);
        if($user){
        return response()->json(['status'=>200,'data'=>$user,'message'=>'User found!']);
        }else{
            return response()->json(['status'=>400,'data'=>$user,'message'=>'User Not found!']);
        }
        }catch(\Exception $e){
            return response()->json(['status'=>404,'data'=>$user,'message'=>$e]);

        }
    }
}
