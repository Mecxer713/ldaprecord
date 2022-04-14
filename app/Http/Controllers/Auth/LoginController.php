<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Exception;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */

    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'username';
    }

    protected function credentials(Request $request)
    {
        setEnv('AUTH_PROVIDER','0');

        $username = env('AUTH_PROVIDER') == 1 ? 'username' : 'samaccountname';
        return [
            $username => $request->get('username'),
            'password' => $request->get('password'),
        ];
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                $this->username() => 'required|string',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                $error = Json_decode($validator->messages()->toJson());
                return response()->json([
                    'error' => [
                        "message" => $error,
                        "type" => ["ValidationException"],
                    ],
                ], 302);
            } else {
                $data = $this->credentials($request);
                if (Auth::attempt($data)) {
                    return redirect('/home');
                }else{
                    return redirect('login');
                }
            }
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 500);
        }
    }

}
