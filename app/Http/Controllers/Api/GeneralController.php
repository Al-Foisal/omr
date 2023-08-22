<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Course;
use App\Models\CourseRegistration;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class GeneralController extends Controller {

    public function updateProfile(Request $request) {
        $user = User::find(Auth::id());

        if ($request->hasFile('image')) {

            $image_file = $request->file('image');

            if ($image_file) {

                $image_path = public_path($user->image);

                if (File::exists($image_path)) {
                    File::delete($image_path);
                }

                $img_gen   = hexdec(uniqid());
                $image_url = 'images/user/';
                $image_ext = strtolower($image_file->getClientOriginalExtension());

                $img_name    = $img_gen . '.' . $image_ext;
                $final_name1 = $image_url . $img_gen . '.' . $image_ext;

                $image_file->move($image_url, $img_name);
                $user->image = $final_name1;
                $user->save();

            }

        }

        $user->name  = $request->name;
        $user->about = $request->about;
        $user->save();

        return $this->successMessage();

    }

    public function notification() {
        $course = CourseRegistration::where('user_id', auth()->user()->id)
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->pluck('course_id')
            ->toArray();

        $data['all'] = Notification::whereNull('user_id')
            ->whereNull('course_id')
            ->orderByDesc('id')
            ->get()
            ->groupBy(function ($query) {
                return $query->created_at->format('Y-m-d');
            });

        $data['individual'] = Notification::where('user_id', Auth::id())
            ->whereNull('course_id')
            ->orderByDesc('id')
            ->get()
            ->groupBy(function ($query) {
                return $query->created_at->format('Y-m-d');
            });

        $data['course'] = Notification::whereNull('user_id')
            ->whereIn('course_id', $course)
            ->orderByDesc('id')
            ->get()
            ->groupBy(function ($query) {
                return $query->created_at->format('Y-m-d');
            });

        return $this->successMessage('', $data);
    }

    public function registeredOrSuggestCourses() {
        $data   = [];
        $course = CourseRegistration::where('user_id', auth()->user()->id)
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->pluck('course_id')
            ->toArray();

        $registered_courses_details = Course::whereIn('id', $course)->with([
            'subjects' => [
                'exams' => function ($query) {
                    return $query->where('status', 1);
                },

            ],

        ])
            ->withCount([
                'subjects',
            ])->get();

        foreach ($registered_courses_details as $item) {
            $counter = 0;

            foreach ($item->subjects as $s_item) {

                foreach ($s_item->exams as $e_item) {
                    $e_item['is_completed'] = Answer::where('user_id', Auth::id())->where('exam_id', $e_item->id)->exists();
                    ++$counter;

                }

            }

            $item['completed_exam'] = Answer::where('user_id', Auth::id())->where('course_id', $item->id)->count();
            $item['exams_count']    = $counter;
        }

        $suggested_courses_details = Course::where('status', 1)->whereNotIn('id', $course)
            ->withCount([
                'subjects',
                'exams',
            ])->get();

        $data['registered_courses'] = $registered_courses_details;
        $data['suggested_courses']  = $suggested_courses_details;

        return $this->successMessage('ok', $data);
    }

    public function search(Request $request) {
        $data   = [];
        $course = CourseRegistration::where('user_id', auth()->user()->id)
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->pluck('course_id')
            ->toArray();

        $suggested_courses_details = Course::where('status', 1)->whereNotIn('id', $course)
            ->where('name', 'LIKE', '%' . $request->search . '%')
            ->withCount([
                'subjects',
                'exams',
            ])->get();

        $data['suggested_courses'] = $suggested_courses_details;

        return $this->successMessage('ok', $data);
    }

    public function storeCourseRegistration(Request $request) {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
        ]);

        if ($validator->fails()) {

            return $this->validationMessage($validator->errors());
        }

        if (
            !CourseRegistration::where('user_id', auth()->user()->id)
            ->where('course_id', $request->course_id)
            ->exists()
        ) {
            CourseRegistration::create([
                'user_id'   => auth()->user()->id,
                'course_id' => $request->course_id,
                'order_id'  => $request->order_id,
            ]);

            return $this->successMessage('Course registration successful! Wait for admin approval.');
        } else {
            return $this->errorMessage('You have registered this course before! Wait for admin approval.');
        }

    }

}
