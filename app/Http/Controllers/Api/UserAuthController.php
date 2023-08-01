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

            if ($last_user) {
                $registration_id = str_pad((int) $last_user->registration_id + 1, 8, "0", STR_PAD_LEFT);
            } else {
                $registration_id = str_pad((int) 1, 8, "0", STR_PAD_LEFT);
            }

            $user = User::create([
                'name'            => $request->name,
                'phone'           => $request->phone,
                'email'           => $request->email,
                'password'        => bcrypt($request->password),
                'registration_id' => $registration_id,
            ]);

            $otp = rand(111111, 999999);
            Mail::to($user->email)->send(new PasswordResetOtp($otp));
            ForgotPasswordOtp::create([
                'otp'   => $otp,
                'email' => $user->email,
            ]);

            DB::commit();

            return $this->successMessage('Your account created and a 6 digits code has send to your email!', $otp);

        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->errorMessage($th);
        }

    }

    public function verifyOtp(Request $request) {

        $this->apiAuthCheck();

        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'email' => 'required',
                'otp'   => 'required',
            ]);

            if ($validator->fails()) {
                return $this->validationMessage($validator->errors());
            }

            $otp = DB::table('forgot_password_otps')
                ->where('email', $request->email)
                ->where('otp', $request->otp)
                ->first();

            if (!$otp) {
                return $this->errorMessage('Invalid email or OTP!!', $request->otp);
            }

            $user                    = User::where('email', $request->email)->first();
            $user->email_verified_at = now();
            $user->status            = 1;
            $user->save();

            DB::table('forgot_password_otps')
                ->where('email', $request->email)
                ->where('otp', $request->otp)
                ->delete();

            DB::commit();

            return $this->successMessage('Your account verified successfully!!', '');

        } catch (\Throwable $th) {

            DB::rollBack();

            return $this->errorMessage('Something went wrong!!', '');
        }

    }

    public function resendOtp(Request $request) {

        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);

        if ($validator->fails()) {

            return $this->validationMessage($validator->errors());
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->successMessage('Invalid accoutn!');
        }

        $otp = rand(111111, 999999);
        Mail::to($request->email)->send(new PasswordResetOtp($otp));
        ForgotPasswordOtp::create([
            'otp'   => $otp,
            'email' => $request->email,
        ]);

        return $this->successMessage('An 6 digit code has been sent to your email!', $otp);

    }

    public function login(Request $request) {
        try {
            $request->validate([
                'email'    => 'required',
                'password' => 'required',
            ]);

            if (!$auth = Auth::attempt([
                'email'    => $request->email,
                'password' => $request->password,
                'status'   => 1,
            ])) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Invalid email or unverified account!!',
                ]);
            }

            $data['user']        = $user        = Auth::user();
            $data['tokenResult'] = $user->createToken('authToken')->plainTextToken;

            return $this->successMessage('ok', $data);

        } catch (Exception $error) {
            return response()->json([
                'status'  => false,
                'message' => 'Error in Login',
            ]);
        }

    }

    public function storeForgotPassword(Request $request) {

        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);

        if ($validator->fails()) {

            return $this->validationMessage($validator->errors());
        }

        $otp = rand(111111, 999999);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->errorMessage('This email is no longer with our records!!');
        }

        Mail::to($request->email)->send(new PasswordResetOtp($otp));

        ForgotPasswordOtp::create([
            'otp'   => $otp,
            'email' => $request->email,
        ]);

        return $this->successMessage('An 6 digit code has been sent to your email!', $otp);

    }

    public function resetPassword(Request $request) {
        $validator = Validator::make($request->all(), [
            'email'    => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        if ($validator->fails()) {

            return $this->validationMessage($validator->errors());
        }

        $password = DB::table('forgot_password_otps')->where('email', $request->email)->first();

        if (!$password) {
            return $this->errorMessage('Something went wrong');
        }

        $user = User::orWhere('email', $request->email)->first();

        if ($user && $password) {
            $user->update(['password' => bcrypt($request->password)]);
            $user->save();

            $password = ForgotPasswordOtp::where('email', $request->email)->delete();

            return $this->successMessage('New password reset successfully!!');
        } else {
            return $this->errorMessage('The email is no longer our record!!');
        }

    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->logout();

        return response()->json([
            'status'  => true,
            'message' => 'Successfully logged out',
        ]);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token) {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60,
        ]);
    }

}
