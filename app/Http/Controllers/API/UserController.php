<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\isValidPassword;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function createUser(Request $request) {

        $aValidationData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => $request->password
        ];

        $aValidationRules = [
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')
            ],
            'name' => [
                'required',
                'string'
            ],
            'phone' => [
                'required', 
                'numeric',
                'digits:10',
                Rule::unique('users', 'phone')
            ],
            'password' => [
                'required',
                'min:8',
                Rule::unique('users', 'password')
            ]
        ];

        $aValidationMessages = [
            'email.required' => 'The Email address is Required',
            'email.unique' => 'This Email address has already been taken',

            'name.required' => 'The Name filed is Required',
            'name.string' => 'The Name filed should be String',

            'phone.required' => 'The Phone No is Required',
            'phone.unique' => 'The Phone No Should be Unique',
            'phone.numeric' => 'The Phone No Should be Numeric',
            'phone.digit' => 'The Phone No Should be 10 digits',
            
            'password.required' => 'The Password is Required',
            'password.unique' => 'The Password Should be Unique',
            'password.min' => 'The Password Should have minimun 8 Characters'
        ];

        $validator = Validator($aValidationData, $aValidationRules, $aValidationMessages);
        if ($validator->fails()) {
            $response = array(
                'status' => false,
                'code' => 400,
                'message' => 'Validation error occured',
                'error_message' => $validator->errors()
            );
            $responseCode = 400;
            return response()->json($response, $responseCode);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => bcrypt($request->password)
            ]);
            if ($user->id) {
                $response = array(
                    'status' => true,
                    'code' => 200,
                    'message' => 'User Created Successfuly',
                    'user_id' => $user->id
                );
                $responseCode = 200;
            } else {
                $response = array(
                    'status' => false,
                    'code' => 400,
                    'message' => 'User Not Created'
                );
                $responseCode = 400;
            }
            return response()->json($response, $responseCode);
        } catch (Exception $exception) {
            $response = array(
                'status' => false,
                'message' => 'API failed due to an error',
                'error' => $exception->getMessage()
            );
            $responseCode = 500;
            return response()->json($response, $responseCode);
        }
        
    }

    public function getUser() {
        $aUserData = User::query()->orderByDesc('id')->paginate(5);
        // $aUserData = User::select('id', 'name', 'phone', 'email')->get();
        $response = array(
            'status' => true,
            'code' => 200,
            'message' => COUNT($aUserData).' User Data fetched successfully',
            'data' => $aUserData
        );
        $responseCode = 200;
        return response()->json($response, $responseCode);
    }

    public function getUserDetail($iID) {
        $aUserData = User::select('id', 'name', 'email', 'phone')->find($iID);
        if (!empty($aUserData)) {
            $sMessage = 'User Data Found';
        } else {
            $sMessage = 'User Data Not Found';
        }
        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => $sMessage,
            'data' => $aUserData
        ], 200);
    }

    public function updateUser($iID, Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,'.$iID,
            'phone' => 'required|numeric|unique:users,phone,'.$iID
        ]);

        if ($validator->fails()) {
            $response = array(
                'status' => false,
                'code' => 500,
                'message' => 'Validation Error Occured',
                'error_message' => $validator->errors()
            );
            $responseCode = 400;
            return response()->json($response, $responseCode);
        }

        $aUserData = User::select('name', 'email', 'phone')->find($iID);
        if (!empty($aUserData)) {
            $user = User::findOrFail($iID);
            $user->update($request->all());
            $response = array(
                'status' => true,
                'code' => 200,
                'message' => 'User Updated Successfuly',
                'user_id' => $iID
            );
            $responseCode = 200;
        } else {

            $response = array(
                'status' => false,
                'code' => 400,
                'message' => 'User Not Updated Successfuly',
                'user_id' => $iID
            );
            $responseCode = 400;
        }
        return response()->json($response, $responseCode);
    }

    public function deleteUser($iID) {
        $aUserData = User::find($iID);
        if (!empty($aUserData)) {
            $aResult = $aUserData->delete();
            if ($aResult) {
                $response = array(
                    'status' => true,
                    'code' => 200,
                    'message' => 'User deleted Successfuly'
                );
                $responseCode = 200;
            } else {
                $response = array(
                    'status' => false,
                    'code' => 400,
                    'message' => 'Something went Wrong'
                );
                $responseCode = 200;
            }
        } else {
            $response = array(
                'status' => false,
                'code' => 404,
                'message' => 'User Not Found'
            );
            $responseCode = 404;
        }
        return response()->json($response, $responseCode);
    }

    public function login(Request $request) {
        $validator = Validator::make($request->all(),[
            'email' => 'required',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            $response = array(
                'status' => false,
                'code' => 400,
                'message' => 'Validation Error Occured',
                'error_message' => $validator->errors()
            );
            $responseCode = 400;
        } else {
            $credentials = array('email' => $request->email, 'password' => $request->password);
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $sToken = $user->createToken('login')->accessToken;
                $response = array(
                    'status' => true,
                    'message' => 'Login Successfull',
                    'code' => 200,
                    'token' => $sToken
                );
                $responseCode = 200;
            } else {
                $response = array(
                    'status' => false,
                    'message' => 'Invalid Credentials',
                    'code' => 401
                );
                $responseCode = 401;
            }
        }
        return response()->json($response, $responseCode);
    }
    
    public function unauthenticate() {
        return response()->json([
            'status' => false,
            'message' => 'Only Authorized user can access',
            'code' => 401
        ], 401);
    }

    public function logout() {
        $user = Auth::user();
        if (!empty($user)) {
            $user->tokens->each(function ($token, $key) {
                $token->delete();
            });
            return response()->json([
                'status' => true,
                'message' => 'User Logout Successfully',
                'code' => 200
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized Credentials',
                'code' => 401
            ], 401);
        }
    }
}
