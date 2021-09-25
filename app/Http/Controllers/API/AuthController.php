<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\AuthRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    protected $authRepository;

    public function __construct()
    {
        $this->authRepository = new AuthRepository();
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), ['email' => 'required|email', 'password' => 'required']);
            if ($validator->fails()) {
                throw new \Exception($validator->getMessageBag()->first(), 201);
            }

            $user = $this->authRepository->getUserByEmail($request->get('email'));
            if (!isset($user)) {
                throw new \Exception('Email is not registered with us.', 201);
            }

            $data = [
                'email' => $request->email,
                'password' => $request->password
            ];

            if (auth()->attempt($data)) {
                $user = $this->authRepository->getUserByEmail($request->get('email'));
                $user['token'] = auth()->user()->createToken('auth-token')->plainTextToken;

                return response()
                    ->json([
                        'success' => true,
                        'message' => 'You have logged in successfully.',
                        'data'    => [
                            'user' => $user
                        ]
                    ], 200);
            }

            return response()->json(['error' => 'Your email or password is incorrect.'], 401);
        } catch (\Exception $e) {
            return response()
                ->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 201);
        }
    }

    public function register(Request $request)
    {
        try {
            $this->validate($request, [
                'firstname' => 'required|min:4',
                'lastname' => 'required|min:4',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8',
            ]);

            $data = $request->all();
            $data['password'] = bcrypt($request->password);

            $user = $this->authRepository->store($data);
            $user['token'] = $user->createToken('auth-token')->plainTextToken;

            return response()
                ->json([
                    'success' => true,
                    'message' => 'Your account has been created successfully.',
                    'data'    => [
                        'user_data' => $user
                    ]
                ], 200);
        } catch (\Exception $e) {
            return response()
                ->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 201);
        }
    }
}
