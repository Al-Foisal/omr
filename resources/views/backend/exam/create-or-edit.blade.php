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
                                    <select name="course_id" id="" class="form-control" required>
                                        <option value="">select</option>
                                        @foreach ($course as $item)
                                            <option value="{{ $item->id }}"
                                                @if ($data && $data->course_id == $item->id) {{ 'selected' }} @endif>
                                                {{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Select Subject*</label>
                                    <select multiple class="form-control" name="subject_id[]" id="subject_id" required>
                                        @foreach ($subject as $item)
                                            <option value="{{ $item->id }}"
                                                @if ($data && in_array($item->id, explode(',', $data->subject_id))) {{ 'selected' }} @endif>
                                                {{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Select Topic*</label>
                                    <select multiple class="form-control" name="subject_topic_id[]" id="subject_topic_id"
                                        required>
                                        @if ($data && $data_topic)
                                            @foreach ($data_topic as $topic)
                                                <option value="{{ $topic->id }}"
                                                    @if ($data && in_array($topic->id, explode(',', $data->subject_topic_id))) {{ 'selected' }} @endif>
                                                    {{ $topic->name }}
                                                </option>
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
            $('#subject_id').on('change', function() {
                var subject_id = $(this).val();
                console.log(subject_id);
                if (subject_id) {
                    $.ajax({
                        url: "{{ url('admin/exam/get-subject-wise-topic') }}",
                        type: "post",
                        dataType: "json",
                        data: {
                            subject_id: subject_id
                        },
                        success: function(data) {
                            var d = $('#subject_topic_id')
                                .empty();
                            $.each(data, function(key, value) {
                                $('#subject_topic_id')
                                    .append(
                                        '<option value="' +
                                        value.id + '">' + value
                                        .name + '</option>');
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
