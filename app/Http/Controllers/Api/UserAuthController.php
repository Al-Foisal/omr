<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetOtp;
use App\Models\ForgotPasswordOtp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UserAuthController extends Controller {
    public function register(Request $request) {
        DB::beginTransaction();

        try {

            $validator = Validator::make($request->all(), [
                'name'     => 'required',
                'email'    => 'required|unique:users|email',
                'phone'    => 'nullable|unique:users',
                'password' => 'required|min:8',
            ]);

            if ($validator->fails()) {

                return $this->validationMessage($validator->errors());
            }

            $last_user = User::latest()->first();

            $registration_id = str_pad($last_user, 8, "0", STR_PAD_LEFT);

            $user = User::create([
                'name'            => $request->name,
                'phone'           => $request->phone,
                'email'           => $request->email,
                'password'        => bcrypt($request->password),
                'registration_id' => $registration_id,
            ]);

            DB::commit();

            return $this->successMessage('Your account created successfully!', $user);

        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->errorMessage($th);
        }

    }

    public function verifyOtp(Request $request) {

        DB::beginTransaction();

        try {
            $request->validate([
                'email_or_phone' => 'required',
                'otp'            => 'required|min:6',
            ]);

            if (!filter_var($request->email_or_phone, FILTER_VALIDATE_EMAIL)) {
                $otp = DB::table('forgot_password_otps')
                    ->where('email', $request->email_or_phone)
                    ->where('otp', $request->otp)
                    ->first();

                if (!$otp) {
                    return $this->errorMessage('Invalid phone number or OTP!!', $request->otp);
                }

            } else {

                $otp = DB::table('forgot_password_otps')
                    ->where('email', $request->email_or_phone)
                    ->where('otp', $request->otp)
                    ->first();

                if (!$otp) {
                    return $this->errorMessage('Invalid email or OTP!!', $request->otp);
                }

            }

            $otp->delete();

            DB::commit();

            return $this->successMessage('Your account verified successfully!!', '');

        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->errorMessage('Something went wrong!!', '');
        }

    }

    public function resendOtp(Request $request) {

        $validator = Validator::make($request->all(), [
            'email_or_phone' => 'required',
        ]);

        if ($validator->fails()) {

            return $this->validationMessage($validator->errors());
        }

        if (!filter_var($request->email_or_phone, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('phone', $request->email_or_phone)->first();

            if (!$user) {
                return $this->errorMessage('Invalid accoutn!');
            }

            $otp = rand(11111, 99999);
            ForgotPasswordOtp::create([
                'otp'            => $otp,
                'email_or_phone' => $request->email_or_phone,
            ]);

            return $this->successMessage('An 5 digit code has been sent to your ********' . substr($request->email_or_phone, -3), $otp);

        } else {

            $user = User::where('email', $request->email_or_phone)->first();

            if (!$user) {
                return $this->successMessage('Invalid accoutn!');
            }

            $otp = rand(11111, 99999);
            Mail::to($request->email_or_phone)->send(new PasswordResetOtp($otp));
            ForgotPasswordOtp::create([
                'otp'            => $otp,
                'email_or_phone' => $request->email_or_phone,
            ]);

            return $this->successMessage('An 5 digit code has been sent to your email!', $otp);
        }

        return $this->errorMessage('Something went wrong!');
    }

    public function login(Request $request) {
        try {
            $request->validate([
                'email_or_phone' => 'required',
                'password'       => 'required',
            ]);

            if (!filter_var($request->email_or_phone, FILTER_VALIDATE_EMAIL)) {

                if (!Auth::attempt([
                    'email'    => $request->email_or_phone,
                    'password' => $request->password,
                    'status'   => 1,
                ])) {
                    return response()->json([
                        'status'  => false,
                        'message' => 'Invalid phone number or unauthorized account!!',
                    ]);
                }

                $user = Auth::user();

                $tokenResult = $user->createToken('authToken')->plainTextToken;

                return response()->json([
                    'status'       => true,
                    'token_type'   => 'Bearer',
                    'access_token' => $tokenResult,
                    'auth_type'    => 'mobile',
                ]);

            } else {

                if (!Auth::attempt([
                    'email'    => $request->email_or_phone,
                    'password' => $request->password,
                    'status'   => 1,
                ])) {
                    return response()->json([
                        'status'  => false,
                        'message' => 'Invalid email or unauthorized account!!',
                    ]);
                }

                $user = Auth::user();

                $tokenResult = $user->createToken('authToken')->plainTextToken;

                return response()->json([
                    'status'       => true,
                    'token_type'   => 'Bearer',
                    'access_token' => $tokenResult,
                    'auth_type'    => 'email',
                ]);

            }

        } catch (Exception $error) {
            return response()->json([
                'status'  => false,
                'message' => 'Error in Login',
            ]);
        }

    }

    public function storeForgotPassword(Request $request) {

        $validator = Validator::make($request->all(), [
            'email_or_phone' => 'required',
        ]);

        if ($validator->fails()) {

            return $this->validationMessage($validator->errors());
        }

        $otp = rand(11111, 99999);

        if (!filter_var($request->email_or_phone, FILTER_VALIDATE_EMAIL)) {
            // file_get_contents('https://aamarsms.com/otp?user=Educity&password=edu@321&to=' . $request->email_or_phone . '&text=Your OTP is ' . $otp);
            $user = User::where('phone', $request->email_or_phone)->first();

            if (!$user) {
                return $this->errorMessage('This phone number is no longer with our records!!');
            }

            ForgotPasswordOtp::create([
                'otp'            => $otp,
                'email_or_phone' => $request->email_or_phone,
            ]);

            return $this->successMessage('An 5 digit code has been sent to your ********' . substr($request->email_or_phone, -3), $otp);
        } else {
            $user = User::where('email', $request->email_or_phone)->first();

            if (!$user) {
                return $this->errorMessage('This email is no longer with our records!!');
            }

            Mail::to($request->email_or_phone)->send(new PasswordResetOtp($otp));

            ForgotPasswordOtp::create([
                'otp'            => $otp,
                'email_or_phone' => $request->email_or_phone,
            ]);

            return $this->successMessage('An 5 digit code has been sent to your email!', $otp);
        }

        return $this->errorMessage('Something went wrong!');

    }

    public function resetPassword(Request $request) {
        $validator = Validator::make($request->all(), [
            'email_or_phone' => 'required',
            'password'       => 'required|confirmed|min:8',
        ]);

        if ($validator->fails()) {

            return $this->validationMessage($validator->errors());
        }

        $password = DB::table('forgot_password_otps')->where('email_or_phone', $request->email_or_phone)->first();

        if (!$password) {
            return $this->errorMessage('Something went wrong');
        }

        $user = User::orWhere('email', $request->email_or_phone)->orWhere('phone', $request->email_or_phone)->first();

        if ($user && $password) {
            $user->update(['password' => bcrypt($request->password)]);
            $user->save();

            $password = ForgotPasswordOtp::where('email_or_phone', $request->email_or_phone)->delete();

            return $this->successMessage('New password reset successfully!!');
        } else {
            return $this->errorMessage('The email is no longer our record!!');
        }

    }

}
