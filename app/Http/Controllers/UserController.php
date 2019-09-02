<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(){
        $users = User::where('lat', '!=', '')->where('long', '!=', '')->get();
        $data = [];

        foreach ($users as $key => $user) {
            $data[$key]['id'] = $user->id;
            $data[$key]['name'] = $user->name;
            $data[$key]['latitude'] = $user->latitude;
            $data[$key]['longitude'] = $user->longitude;
            $data[$key]['profile_image'] = $user->profile_image;
            $data[$key]['avatar'] = $user->avatar;
        }

        return response()->json(['users' => $data], 200);
    }

    public function update(Request $request)
    {
        $let_long = $request->all();
        $user = User::findOrFail($let_long['id']);
        $user->latitude = $let_long['lat'];
        $user->longitude = $let_long['long'];
        $user->save();

        return response()->json(['users' => $user], 200);
    }

    public function updateLocationByLogout($id){
        $user = User::findOrFail($id);
        $user->lat = '';
        $user->long = '';
        $user->save();

        return response()->json(['success'=>'success'],200);
    }

    //User View
    public function userProfile(){

        $user = User::findOrFail(Auth::user()->id);
        $data = [
            "name" => $user->name,
            "email" => $user->email,
            "image" => $user->avatar,
            "lat" => $user->latitude,
            "long" => $user->longitude,
            "location" => $user->location,
        ];

        return $data;
    }

    public function userProfileUpdate(Request $request){
        $user = User::findOrFail(Auth::user()->id);
        $user->name = $request->name;
        $user->location = $request->location;
        $user->latitude = $request->lat;
        $user->longitude = $request->long;
        $user->save();

        return response()->json([
            'success' => 'Your Profile Updated',
            'obj' => $user,
        ], 200);
    }

    public function AuthCheck(){
        return Auth::user();
    }
}

