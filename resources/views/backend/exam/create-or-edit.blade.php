@extends('backend.layouts.master')
@section('title', request('id') ? 'Update' : 'Create' . ' Course Exam')
@section('backend')
    <!-- Content Header (Course Exam header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ request('id') ? 'Update' : 'Create' }} Course Exam</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Create Course Exam</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary">
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form
                            action="{{ request('id') ? route('admin.exam.storeOrUpdate', $data->id) : route('admin.exam.storeOrUpdate') }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            @if (request('id'))
                                @method('put')
                            @endif
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="">Select Course Name*</label>
                                    <select name="course_id" id="" class="form-control" required @if($data) {{ 'disabled' }} @endif>
                                        <option value="">select</option>
                                        @foreach ($course as $item)
                                            <option value="{{ $item->id }}"
                                                @if ($data && $data->course_id == $item->id) {{ 'selected' }} @endif>
                                                {{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Select Subject*</label>
                                    <select name="subject_id" id="" class="form-control" required @if($data) {{ 'disabled' }} @endif>
                                        <option value="">select</option>
                                        @if ($data)
                                            @foreach ($subject as $item)
                                                <option value="{{ $item->id }}"
                                                    @if ($data && $data->subject_id == $item->id) {{ 'selected' }} @endif>
                                                    {{ $item->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="name">Exam Name*</label>
                                    <input type="text" class="form-control" id="name" placeholder="Name"
                                        name="name" value="{{ $data->name ?? '' }}" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="total_question">Total Question*</label>
                                            <input type="number" class="form-control" id="total_question" placeholder="80"
                                                name="total_question" value="{{ $data->total_question ?? '' }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="per_question_positive_mark">Per Question Mark*</label>
                                            <input type="text" class="form-control" id="per_question_positive_mark"
                                                placeholder="1.00" name="per_question_positive_mark"
                                                value="{{ $data->per_question_positive_mark ?? '' }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="per_question_negative_mark">Per Question Negative Mark*</label>
                                            <input type="text" class="form-control" id="per_question_negative_mark"
                                                placeholder="0.5" name="per_question_negative_mark"
                                                value="{{ $data->per_question_negative_mark ?? '' }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
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
