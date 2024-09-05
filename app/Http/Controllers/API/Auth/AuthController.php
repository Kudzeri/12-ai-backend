<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\SmsService;
use Exception;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:200',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => $validator->errors()
                ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
            ]);

            $user->sendEmailVerificationNotification();
            $token = $user->createToken('register_token')->plainTextToken;

            return response()->json([
                'status' => Response::HTTP_OK,
                'data' => $user,
                'access_token' => $token,
                'type' => 'Bearer'
            ], Response::HTTP_OK);
        } catch (Exception $e){
        return response()->json([
            'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            'message' => $e->getMessage()
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function login(Request $request){
        $credentials = $request->only('email', 'password');
        $user = User::where('email', $request->email)->first();

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        if (!Auth::attempt($credentials)) {
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => Response::HTTP_UNAUTHORIZED,
                    'message' => 'Invalid Credentials'
                ], Response::HTTP_UNAUTHORIZED);
            }
        }

        $token = $user->createToken('login_token')->plainTextToken;
        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'Logged in successfully',
            'data_user' => $request->user(),
            'access_token' => $token,
            'type' => 'Bearer'
        ], Response::HTTP_OK);
    }

    public function logout(Request $request){
        Auth::user()->tokens()->delete();
        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'Logged out'
        ], Response::HTTP_OK);
    }

    public function unauthenticated(Request $request){
        $token = $request->bearerToken();
        if (!$token){
            return response()->json([
                'status' => Response::HTTP_UNAUTHORIZED,
                'message' => 'Please sign in again',
            ], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function sendVerificationEmail(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'Verification link sent to your email'
        ], Response::HTTP_OK);
    }

    public function verifyEmail(Request $request){
        if(!URL::hasValidSignature($request)){
            return response()->json([
                'status' => Response::HTTP_UNAUTHORIZED,
                'message' => 'Invalid or expired verification link.'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = User::findOrFail($request->route('id'));

        if($user->hasVerifiedEmail()){
            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Your email address is already verified.'
            ], Response::HTTP_OK);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'Your email address is verified.'
        ], Response::HTTP_OK);
    }

    public function resendVerificationEmail(Request $request){
        $request->user()->sendEmailVerificationNotification();

        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'Verification link sent to your email'
        ], Response::HTTP_OK);
    }

    public function sendPhoneVerificationCode(Request $request, SmsService $service)
    {
        $request->validate([
            'phone' => 'required|string|regex:/^\+?[1-9]\d{1,14}$/'
        ]);

        $user = auth()->user();
        $code = Str::random(6);
        $user->phone_verification_code = $code;
        $user->save();

        $service->sendVerificationCode($request->phone, $code);

        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'Verification code sent to your phone.'
        ], Response::HTTP_OK);
    }

    public function verifyPhoneVerificationCode(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|regex:/^\+?[1-9]\d{1,14}$/',
            'code' => 'required|string'
        ]);

        $user = auth()->user();


        if ($user->phone_verification_code === $request->code) {
            $user->phone_verified_at = now();
            $user->phone_verification_code = null;
            $user->save();

            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Phone number verified successfully.'
            ], Response::HTTP_OK);
        }

        return response()->json([
            'status' => Response::HTTP_UNAUTHORIZED,
            'message' => 'Invalid verification code.'
        ], Response::HTTP_UNAUTHORIZED);
    }
}
