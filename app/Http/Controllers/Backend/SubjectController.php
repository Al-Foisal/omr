<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index() {
        $data = Subject::all();

        return view('backend.subject.index', compact('data'));
    }

    public function createOrEdit($id = null) {

        if ($id) {
            $data = Subject::findOrFail($id);
        } else {
            $data = null;
        }

        return view('backend.subject.create-or-edit', compact('data'));
    }

    public function storeOrUpdate(Request $request, $id = null) {
        Subject::updateOrCreate(
            [
                'id' => $id,
            ],
            [
                'name' => $request->name,
            ]
        );

        return to_route('admin.subject.index')->withToastSuccess('Data update successfully!!');
    }

    public function updateStatus(Request $request) {
        $data         = Subject::findOrFail($request->id);
        $data->status = $data->status == 1 ? 0 : 1;
        $data->save();

        return to_route('admin.subject.index')->withToastSuccess('Status updated successfully!!');
    }
}
