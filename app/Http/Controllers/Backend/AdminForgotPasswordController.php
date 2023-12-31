<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordLink;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AdminForgotPasswordController extends Controller
{
    public function forgotPassword() {
        return view('backend.auth.forgot-password');
    }

    public function storeForgotPassword(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return back()->with('toast_error', $validator->messages()->all())->withInput();
        }

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin) {
            return redirect()->back()->withToastError('This email is no longer with our records!!');
        }

        $url = route('admin.auth.resetPassword', [$request->_token, 'email' => $request->email]);

        Mail::to($request->email)->send(new ResetPasswordLink($url));

        DB::table('password_reset_tokens')->insert([
            'token'      => $request->_token,
            'email'      => $request->email,
            'created_at' => now(),
        ]);

        return redirect()->back()->withToastSuccess('We have sent a fresh reset password link to your email!!');
    }
}
