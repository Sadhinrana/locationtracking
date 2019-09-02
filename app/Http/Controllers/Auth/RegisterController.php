<?php

namespace App\Http\Controllers\Auth;

use Image;
use App\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'profile_image' => ['required', 'image', 'max:2048'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        if (Input::file('profile_image')->isValid()) {
            //get filename with extension
            $filenamewithextension = Input::file('profile_image')->getClientOriginalName();

            //get filename without extension
            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);

            //get file extension
            $extension = Input::file('profile_image')->getClientOriginalExtension();

            //filename to store
            $filenametostore = $filename.'_'.time().'.'.$extension;

            //Upload File
            Input::file('profile_image')->storeAs('public/profile_images', $filenametostore);
            Input::file('profile_image')->storeAs('public/profile_images/thumbnail', $filenametostore);

            //Resize image here
            $thumbnailpath = public_path('storage/profile_images/thumbnail/'.$filenametostore);
            $img = Image::make($thumbnailpath)->resize(30, 30, function($constraint) {
                $constraint->aspectRatio();
            });
            $img->save($thumbnailpath);
        }

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'profile_image' => 'storage/profile_images/'.$filenametostore,
            'avatar' => 'storage/profile_images/thumbnail/'.$filenametostore,
            'password' => Hash::make($data['password']),
        ]);
    }
}
