<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseRegistration;
use App\Models\User;
use App\Services\FCMService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $data = [];
        $data['total_courses'] = Course::count();
        $data['total_students'] = User::count();
        $data['total_registration'] = CourseRegistration::where('status', 0)->count();
        $data['total_month_enroll'] = CourseRegistration::where('status', 1)->whereMonth('created_at', date("Y-m-d"))->count();
        $data['total_year_enroll'] = CourseRegistration::where('status', 1)->whereYear('created_at', date("Y-m-d"))->count();
        $data['total_enroll'] = CourseRegistration::where('status', 1)->count();

        return view('backend.dashboard', $data);
    }

    public function fcmnotification_send(Request $request)
    {
        $title = $request->title;
        $body = $request->body;
        $id = $request->user_id;

        $user = User::findOrFail($id);

        if (isset($user->fcm_token)) {
            FCMService::send(
                $user->fcm_token,
                [
                    'title' => $title,
                    'body' => $body,
                ]
            );
        }
        return redirect()->back()->withToastSuccess('Notification send successfully!!');
    }

    public function fcmnotification_send_all(Request $request)
    {
        $title = $request->title;
        $body = $request->body;

        $user_list = User::get();
        foreach ($user_list as $user){
            if (isset($user->fcm_token)) {
                FCMService::send(
                    $user->fcm_token,
                    [
                        'title' => $title,
                        'body' => $body,
                    ]
                );
            }
        }

        return redirect()->back()->withToastSuccess('Notification send successfully!!');
    }

    public function fcmnotification()
    {
        $data = [];
        return view('backend.fcm_notification.notification', $data);
    }

    public function store_device_token(Request $request)
    {
        $token = $request->token;

        $user = Auth::user();
        if (!isset($user)){
            return $this->errorMessage('Unauthorized user');
        }

        User::where('id', $user->id)->update([
            'fcm_token' => $token
        ]);

        return $this->successMessage('Device token store successfully');
    }
}
