@extends('backend.layouts.master')
@section('title', request('exam_id') ? 'Update' : 'Create' . ' Exam')
@section('cssStyle')
    <style>
        .hide {
            display: none;
        }
    </style>
@endsection
@section('backend')
    <!-- Content Header (Course Exam header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ request('exam_id') ? 'Update' : 'Create' }} Exam for "{{ $data->name }}" Course</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active"> Exam</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-2"></div>
                <div class="col-lg-8">
                    <div class="card card-primary">
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form
                            action="{{ request('exam_id') ? route('admin.course.storeOrUpdateExam', [$data->id, $exam->id]) : route('admin.course.storeOrUpdateExam', $data->id) }}"
                            method="POST">
                            @csrf
                            {{-- @if (request('exam_id'))
                                @method('put')
                            @endif --}}
                            <input type="hidden" name="course_id" value="{{ $data->id }}">
                            <input type="hidden" name="exam_id" value="{{ $exam->id ?? null }}">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="">Select Subject*</label>
                                    <select name="subject_id" id="" class="form-control" required
                                        @if ($exam) {{ 'disabled' }} @endif>
                                        <option value="">select</option>
                                        @if ($data)
                                            @foreach ($subject as $item)
                                                <option value="{{ $item->id }}"
                                                    @if ($exam && $item->id == $exam->subject_id) {{ 'selected' }} @endif>
                                                    {{ $item->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="name">Exam Name*</label>
                                    <input type="text" class="form-control" id="name" placeholder="Name"
                                        name="name" value="{{ $exam->name ?? '' }}" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="total_question">Total Question*</label>
                                            <input type="number" class="form-control" id="total_question" placeholder="80"
                                                name="total_question" value="{{ $exam->total_question ?? '' }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="per_question_positive_mark">Per Question Mark*</label>
                                            <input type="text" class="form-control" id="per_question_positive_mark"
                                                placeholder="1.00" name="per_question_positive_mark"
                                                value="{{ $exam->per_question_positive_mark ?? '' }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="per_question_negative_mark">Per Question Negative Mark*</label>
                                            <input type="text" class="form-control" id="per_question_negative_mark"
                                                placeholder="0.5" name="per_question_negative_mark"
                                                value="{{ $exam->per_question_negative_mark ?? '' }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="card card-success">
                                    <div class="card-header">
                                        <h3 class="card-title" style="float: left">Topic Name<span
                                                class="text-danger">*</span>
                                        </h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <div class="input-group hdtuto control-group lst increment">
                                                <input type="text" name="topic[]" class="myfrm form-control"
                                                    placeholder="Enter topic name">
                                                <input type="hidden" name="topic_id[]">
                                                <div class="input-group-btn">
                                                    <button class="btn btn-success" type="button"><i
                                                            class="far fa-plus-square"></i></button>
                                                </div>
                                            </div>

                                            <div class="clone hide">
                                                <div class="hdtuto control-group lst input-group" style="margin-top:10px">
                                                    <input type="text" name="topic[]" class="myfrm form-control"
                                                        placeholder="Enter topic name">
                                                    <input type="hidden" name="topic_id[]">
                                                    <div class="input-group-btn">
                                                        <button class="btn btn-danger" type="button"> <i
                                                                class="far fa-minus-square"></i> </button>

                                                    </div>
                                                </div>
                                            </div>

                                            @if (request('exam_id'))
                                                @foreach ($exam->topics as $s_item)
                                                    <div class="clone">
                                                        <div class="hdtuto control-group lst input-group"
                                                            style="margin-top:10px">
                                                            <input type="text" name="topic[]"
                                                                class="myfrm form-control" placeholder="Enter topic name"
                                                                value="{{ $s_item->name }}">
                                                            <input type="hidden" name="topic_id[]"
                                                                value="{{ $s_item->id }}">
                                                            <div>
                                                                <a onclick="return confirm('Are you sure want to {{ $s_item->status == 1 ? 'inactive' : 'active' }} this item?')"
                                                                    href="{{ route('admin.course.updateCourseSubjectTopicStatus', $s_item->id) }}"
                                                                    class="btn btn-outline-{{ $s_item->status == 1 ? 'danger' : 'info' }}"
                                                                    type="button">{{ $s_item->status == 1 ? 'Make as Inactive' : 'Make as Active' }}
                                                                </a>

                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="add"></div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <div class="text-left">
                                    <a href="{{ route('admin.course.createOrEdit',$data->id) }}" class="btn btn-success">Previous</a>
                                </div>
                                <div class="text-right">
                                    @if (request('exam_id'))
                                        <a href="{{ route('admin.course.createOrUpdateExam', $data->id) }}"
                                            class="btn btn-outline-secondary">Cancel</a>
                                    @endif
                                    <button type="submit" class="btn btn-success">Next</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.card -->
                <div class="col-lg-2"></div>

            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    <style>
        .card-title {
            text-align: center;
            font-size: 1.1rem;
            font-weight: 400;
            margin: 0;
            float: unset;
        }
    </style>
    {{-- <section class="content">
        <div class="container-fluid">
            <div class="card card-success">
                <div class="card-header">
                    <h1 class="card-title">
                        <strong>Exam List</strong>
                    </h1>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="">
                                <!-- /.card-header -->
                                <div class="">
                                    <table id="example2" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Action</th>
                                                <th>Exam Details</th>
                                                <th>Course Name</th>
                                                <th>Subject Name</th>
                                                <th>Statue</th>
                                                <th>Created_at</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($all_exam as $item)
                                                <tr>
                                                    <td class="d-flex justify-content-around">
                                                        <form action="{{ route('admin.examQuestion.createOrEdit') }}"
                                                            method="post">
                                                            @csrf
                                                            <input type="hidden" name="exam_id"
                                                                value="{{ $item->id }}">
                                                            <button type="submit"class="btn btn-primary btn-xs">
                                                                Add Question
                                                            </button>
                                                        </form>
                                                        <a href="{{ route('admin.course.createOrUpdateExam', [$item->course_id, $item->id]) }}"
                                                            class="btn btn-info btn-xs"> <i class="fas fa-edit"></i>
                                                            Edit</a>

                                                        <form action="{{ route('admin.exam.updateStatus', $item) }}"
                                                            method="post">
                                                            @csrf
                                                            <button type="submit"
                                                                onclick="return(confirm('Are you sure?'))"
                                                                class="btn btn-{{ $item->status == 1 ? 'danger' : 'success' }} btn-xs">
                                                                {{ $item->status == 1 ? 'Inactive' : 'Active' }}
                                                            </button>
                                                        </form>
                                                    </td>
                                                    <td>
                                                        <b>Exam Name:</b> {{ $item->name }} <br>
                                                        <b>Total question:</b> {{ $item->total_question }} <br>
                                                        <b>Per question mark:</b> {{ $item->per_question_positive_mark }}
                                                        <br>
                                                        <b>Per question negative mark:</b>
                                                        {{ $item->per_question_negative_mark }}
                                                        <br>
                                                    </td>
                                                    <td>{{ $item->course->name }}</td>
                                                    <td>
                                                        {{ $item->subject->name ?? '' }}
                                                        <hr>
                                                        <strong>Topics</strong> <br>
                                                        @foreach ($item->topics as $topic)
                                                            {{ $topic->name }}, <br>
                                                        @endforeach
                                                    </td>
                                                    <td>{{ $item->status == 1 ? 'Active' : 'Inactive' }}</td>
                                                    <td>{{ $item->created_at->format('d F, Y') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                {{ $all_exam->links() }}
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section> --}}
@endsection

@section('jsScript')
    <script>
        $(document).ready(function() {
            $('select[name=course_id]').on('change', function() {
                var course_id = $(this).val();
                console.log(course_id);
                if (course_id) {
                    $.ajax({
                        url: "{{ url('admin/exam/get-course-wise-subject') }}",
                        type: "post",
                        dataType: "json",
                        data: {
                            course_id: course_id
                        },
                        success: function(data) {
                            var d = $('select[name=subject_id]')
                                .empty();
                            $.each(data, function(key, value) {
                                $('select[name=subject_id]')
                                    .append(
                                        '<option value="' +
                                        value.id + '">' + value
                                        .name + '</option>'
                                    );
                            });
                        },
                    });
                } else {
                    alert('danger');
                }
            });
        });
    </script>
@endsection
