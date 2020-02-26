<?php

namespace App\Http\Controllers;

use App\User;
use Exception;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * The request instance.
     * - - -
     * @var Request
     */
    private $request;

    /**
     * Create a new controller instance.
     * - - -
     * @param Request $request
     * @return void
     */
    public function __construct(Request $request) {
        $this->request = $request;
    }

    /**
     * Create a new token.
     * - - -
     * @param  \App\User   $user
     * @return string
     */
    protected function jwt(User $user) {
        $payload = [
            'iss' => "lumen-jwt",
            'sub' => $user->id,
            'iat' => time(),
            'exp' => time() + 60 * 60
        ];

        return JWT::encode($payload, env('JWT_SECRET'));
    }

    /**
     * Authenticate a user and return the token if the provided credentials are correct.
     * - - -
     * @param  \App\User   $user
     * @return mixed
     */
    public function authenticate(User $user) {
        $this->validate($this->request, [
            'username'  => 'required|string',
            'password'  => 'required'
        ]);

        // Find user by username
        $user = User::where('username', $this->request->input('username'))->first();
        if (!$user) {
            return response()->json([
                'error' => 'Username does not exist.'
            ], 400);
        }

        // Verify the password and generate the token
        if (Hash::check($this->request->input('password'), $user->password)) {
            return response()->json([
                'token' => $this->jwt($user)
            ], 200);
        }

        // Bad Request response
        return response()->json([
            'error' => 'Incorrect username of password.'
        ], 400);
    }
}
