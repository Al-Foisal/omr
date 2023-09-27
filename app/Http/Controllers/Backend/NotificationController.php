<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseRegistration;
use App\Models\Notification;
use App\Models\User;
use App\Services\FCMService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller {
    public function index() {
        $data = Notification::orderByDesc('id')->paginate(200);

        return view('backend.notification.index', compact('data'));
    }

    public function create() {
        $data            = [];
        $data['user']    = User::all();
        $data['courses'] = Course::all();

        return view('backend.notification.create', $data);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->with('toast_error', $validator->messages()->all())->withInput();
        }

        $type   = $request->type;
        $user   = null;
        $course = null;
        $notification_title = $request->name;
        $notification_body = $request->details;

        if ($type == 1) {
            $user   = null;
            $course = null;

            $user_list = User::get();
            foreach ($user_list as $single_user){
                if (isset($single_user->fcm_token)) {
                    FCMService::send(
                        $single_user->fcm_token,
                        [
                            'title' => $notification_title,
                            'body' => $notification_body,
                        ]
                    );
                }
            }

        } elseif ($type == 2) {

            if (!$request->student) {
                return back()->withToastError('Student name is required');
            }

            $user   = $request->student;
            $course = null;

            $user_find = User::findOrFail($user);
            if (isset($user_find->fcm_token)) {
                FCMService::send(
                    $user_find->fcm_token,
                    [
                        'title' => $notification_title,
                        'body' => $notification_body,
                    ]
                );
            }

        } else {

            if (!$request->course) {
                return back()->withToastError('Course name is required');
            }

            $user   = null;
            $course = $request->course;

            $enroll_user = CourseRegistration::where('course_id', $course)->get();

            foreach ($enroll_user as $user_id){
                $user_find = User::findOrFail($user_id->user_id);
                if (isset($user_find->fcm_token)) {
                    FCMService::send(
                        $user_find->fcm_token,
                        [
                            'title' => $notification_title,
                            'body' => $notification_body,
                        ]
                    );
                }
            }
        }

        Notification::create([
            'name'      => $request->name,
            'details'   => $request->details,
            'user_id'   => $user,
            'course_id' => $course,
            'type'      => $type,
        ]);

        return back()->withToastSuccess('Notification sent successfully');
    }

    public function delete($id) {
        $data = Notification::find($id);
        $data->delete();

        return back()->withToastSuccess('Notification deleted successfully');
    }

}
