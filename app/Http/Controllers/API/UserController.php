<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $user;
    public function __construct()
    {
        $this->user = new User();
    }
    //
    public function register(Request $request) {
        $this->user->name = $request->get('name');
        $this->user->mobile_number = $request->get('mobile_number');
        $this->user->email = $request->get('email');
        $this->user->password = '12345';

        $userId = $this->user->save();

        if(!$userId) {
            return response()->json([
                'message' => 'Registration Response',
                'status'  => 'error'
            ]);
        }

        return response()->json([
            'user' => User::find($this->user->id),
            'status' => 'ok',
            'message' => 'user registered'
        ]);
        
    }

    public function get($id) {
        return response()->json([
            'data' => [
                'user' => User::find($id)
            ]
        ]);
    }

    public function authenticate(Request $request) {
        if(empty($request->get('key')) || empty($request->get('secret'))) {
            return response()->json([
                'message' => 'Email and Password must not be empty',
                'data' => $request->all()
            ]);
        }

        $user = $this->user->where([
            'mobile_number' => $request->get('key')
        ])->first();

        if(!$user) {
            return response()->json([
                'message' => 'User Not found',
                'status' => 'error'
            ]);
        }

        if($user->password != $request->get('secret')) {
            return response()->json([
                'message' => 'Incorrect Password',
                'status' => 'error'
            ]);
        }

        return response()->json([
            'user' => $user
        ]);
    }

    public function all() {
        return response()->json([
            'data' => [
                'users' => User::all()
            ]
        ]);
    }
}
