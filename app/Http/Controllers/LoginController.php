<?php

namespace App\Http\Controllers;

use App\Exceptions\BaseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login()
    {
        return view('access.login');
    }

    public function signIn(Request $request)
    {
        try {
            $data = [
                'email' => $request->get('username'),
                'password' => $request->get('password'),
            ];

            if (!Auth::attempt($data)) 
                throw new BaseException(__('Invalid Access Credentials.'));

            return redirect(route('home'));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('login'))->withErrors($message);
    }

    public function signOut()
    {
        Auth::logout();

        return redirect(route('login'));
    }
}
