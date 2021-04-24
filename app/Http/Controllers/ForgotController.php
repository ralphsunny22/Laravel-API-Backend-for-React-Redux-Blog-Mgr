<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ForgotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function forgot(ForgotPasswordRequest $request)
    {
        $email = $request->email;
        if(User::where('email', $email)->doesntExist()){
            return response([
                'message' => 'User doesn\'t exist'
            ],400);
        }

        //generate random token
        $token = Str::random(10);

        //insert into password_resets table
        DB::table('password_resets')->insert(['email'=>$email, 'token'=>$token, 'created_at'=>Carbon::now()]);

        //get token
        $passwordReset = DB::table('password_resets')->where('token',$token)->first();

        //send mail, http://localhost:3000/reset/{token}

        return response([
            'message' => 'Check your email',
            'token' => $passwordReset->token,
            'created_at' => $passwordReset->created_at
        ],200);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reset(ResetPasswordRequest $request)
    {
        //hidden field
        $token = $request->token;

        if(!$passwordReset = DB::table('password_resets')->where('token',$token)->first()){
            return response([
                'message' => 'Invalid Token',
            ],400);
        }

        if(!$user = User::where('email',$passwordReset->email)->first()){
            return response([
                'message' => 'User doesn\'t exist'
            ],400);
        }

        //update user password, with new password
        $user->password = Hash::make($request->password);
        
        //save it
        $user->save();

        return response([
            'message' => 'Password reset successfully',
            'password' => $request->password
        ]);
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
