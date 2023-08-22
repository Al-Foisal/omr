<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Notification;
use App\Models\User;
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

        if ($type == 1) {
            $user   = null;
            $course = null;
        } elseif ($type == 2) {

            if (!$request->student) {
                return back()->withToastError('Student name is required');
            }

            $user   = $request->student;
            $course = null;
        } else {

            if (!$request->course) {
                return back()->withToastError('Course name is required');
            }

            $user   = null;
            $course = $request->course;
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
