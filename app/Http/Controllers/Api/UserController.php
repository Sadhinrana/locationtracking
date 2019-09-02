<?php

namespace App\Http\Controllers\Api;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index(){
        $users = $users = User::where('latitude', '!=', '')->where('longitude', '!=', '')->get();
        $data = [];

        foreach ($users as $key => $user) {
            $to = Carbon::now();
            $from = $user->lastSeen;

            $diff_in_minutes = $to->diffInMinutes($from);
            if ($diff_in_minutes <= 5) {
                $data[$key]['id'] = $user->id;
                $data[$key]['name'] = $user->name;
                $data[$key]['latitude'] = $user->latitude;
                $data[$key]['longitude'] = $user->longitude;
                $data[$key]['profile_image'] = $user->profile_image;
                $data[$key]['avatar'] = $user->avatar;
            }
        }

        return response()->json(['users' => $data], 200);
    }

    public function UserForWorkPreview(Request $request){

        $ids = $request->ids;
        $ids = json_decode($ids);

        $users = $users = User::where('latitude', '!=', '')->where('longitude', '!=', '')->whereIn('id',$ids)->get();
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

        if (!empty($user->latitude && $user->longitude)){
            return response()->json(['users' => $user], 200);
        }else{
            $user->latitude = $let_long['lat'];
            $user->longitude = $let_long['long'];
            $user->save();
            return response()->json(['users' => $user], 200);
        }
    }

    public function updateLocationByLogout($id){
        $user = User::findOrFail($id);
        $user->latitude = '';
        $user->longitude = '';
        $user->save();

        return response()->json(['success'=>'success'],200);
    }

    public function all_users(){
        $users = $users = User::all();
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
}
