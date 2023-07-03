@extends('backend.layouts.master')
@section('title', 'Go to exam question')
@section('backend')
    <!-- Content Header (Course Exam header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Go to Exam Question</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Exam Question</li>
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
                        <div class="card-body">
                            <div class="form-group">
                                <label for="">Select Course Name*</label>
                                <select name="course_id" id="course_id" class="form-control" required>
                                    <option value="">select</option>
                                    @foreach ($course as $item)
                                        <option value="{{ $item->id }}"> {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Select Subject*</label>
                                <select name="subject_id" id="subject_id" class="form-control" required>
                                    <option value="">select</option>

                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Select Exam Name*</label>
                                <select name="exam_id" id="exam_id" class="form-control" required>
                                    <option value="">select</option>

                                </select>
                            </div>

                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer">
                            <button type="button" class="btn btn-primary" onclick="goToExamQuestion()">Go to Exam
                                Question</button>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
                <div class="col-lg-2"></div>

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
                            $('select[name=subject_id]')
                                .append(
                                    '<option value="">select subject</option>'
                                );
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
    <script>
        $(document).ready(function() {
            $('select[name=subject_id]').on('change', function() {
                var subject_id = $(this).val();
                var course_id = $("#course_id").val();
                if (subject_id) {
                    $.ajax({
                        url: "{{ url('admin/exam/get-course-wise-subject-exam') }}",
                        type: "post",
                        dataType: "json",
                        data: {
                            course_id: course_id,
                            subject_id: subject_id
                        },
                        success: function(data) {
                            var d = $('select[name=exam_id]')
                                .empty();
                            $('select[name=exam_id]')
                                .append(
                                    '<option value="">select exam</option>'
                                );
                            $.each(data, function(key, value) {
                                $('select[name=exam_id]')
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

        function goToExamQuestion() {
            var course_id = $("#course_id").val();
            var subject_id = $("#subject_id").val();
            var exam_id = $("#exam_id").val();

            if (!course_id) {
                Toast.fire({
                    icon: 'error',
                    title: 'Course name is reqired'
                });
                return;
            }
            if (!subject_id) {
                Toast.fire({
                    icon: 'error',
                    title: 'Subject name is reqired'
                });
                return;
            }
            if (!exam_id) {
                Toast.fire({
                    icon: 'error',
                    title: 'Exam name is reqired'
                });
                return;
            }
            window.location.replace("create-or-edit/" + exam_id);
        }
    </script>
@endsection
