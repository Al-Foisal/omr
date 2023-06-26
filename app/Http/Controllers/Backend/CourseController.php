<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Subject;
use Illuminate\Http\Request;

class CourseController extends Controller {
    public function index() {
        $data = Course::latest()->paginate(100);

        return view('backend.course.index', compact('data'));
    }

    public function createOrEdit($id = null) {

        if ($id) {
            $data = Course::findOrFail($id);
        } else {
            $data = null;
        }

        $subject = Subject::where('status', 1)->orderBy('name')->get();

        return view('backend.course.create-or-edit', compact('data', 'subject'));
    }

    public function storeOrUpdate(Request $request, $id = null) {
        Course::updateOrCreate(
            [
                'id' => $id,
            ],
            [
                'name'          => $request->name,
                'subject_id'    => implode(',', $request->subject_id),
                'details'       => $request->details,
                'purchase_link' => $request->purchase_link,
            ]
        );

        return to_route('admin.course.index')->withToastSuccess('Data update successfully!!');
    }

    public function updateStatus(Request $request) {
        $data         = Course::findOrFail($request->id);
        $data->status = $data->status == 1 ? 0 : 1;
        $data->save();

        return to_route('admin.course.index')->withToastSuccess('Status updated successfully!!');
    }

}
