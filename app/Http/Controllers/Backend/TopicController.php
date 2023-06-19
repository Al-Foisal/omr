<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\SubjectTopic;
use Illuminate\Http\Request;

class TopicController extends Controller {
    public function index() {
        $data = Subject::with([
            'subjectToics' => function ($query) {
                return $query->orderBy('name');
            },
        ])->orderBy('name')->get();

        return view('backend.topic.index', compact('data'));
    }

    public function createOrEdit($id = null) {

        if ($id) {
            $data = SubjectTopic::findOrFail($id);
        } else {
            $data = null;
        }

        $subject = Subject::where('status', 1)->orderBy('name')->get();

        return view('backend.topic.create-or-edit', compact('data', 'subject'));
    }

    public function storeOrUpdate(Request $request, $id = null) {
        SubjectTopic::updateOrCreate(
            [
                'id' => $id,
            ],
            [
                'subject_id' => $request->subject_id,
                'name'       => $request->name,
            ]
        );

        return to_route('admin.topic.index')->withToastSuccess('Data update successfully!!');
    }

    public function updateStatus(Request $request) {
        $data         = SubjectTopic::findOrFail($request->id);
        $data->status = $data->status == 1 ? 0 : 1;
        $data->save();

        return to_route('admin.topic.index')->withToastSuccess('Status updated successfully!!');
    }

}
