<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Role;
use App\Layout;
use App\Organization;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Redirect;
use Sentinel;
use Session;
use Activation;
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
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    
    public function showRegistrationForm()
    {
        $layout = Layout::first();
        $organization_info_list = Organization::select("organization_name", "organization_recordid")->distinct()->get();
        return view('auth.register', compact('layout', 'organization_info_list'));
    }

    public function register(Request $request){

        $validation = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
            ]);

          if ($validation->fails()) {
                return Redirect::back()->withErrors($validation)->withInput();
         }

         $user = Sentinel::register($request->all());
        //Activate the user ** 
         $activation = Activation::create($user);
         $activation = Activation::complete($user, $activation->code);
        //End activation

        if($user){
            $user->user_organization = join(',', $request->organization);
            $user->save();
            $user->organizations()->sync($request->organization);
            $user->roles()->sync([2]); // 2 = client
            Session::flash('message', 'Registration is completed');
            Session::flash('status', 'success');
           return redirect('/'); 
        }
         Session::flash('message', 'There was an error with the registration' );
         Session::flash('status', 'error');
         return Redirect::back();
    }



}
