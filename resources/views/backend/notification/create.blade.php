@extends('backend.layouts.master')
@section('title', 'Create notification')
@section('backend')
    <!-- Content Header (Subject Topic header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Notification</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Notification</li>
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
                        <form action="{{ route('admin.notification.store') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="">Notification Type</label>
                                    <select name="type" id="type" class="form-control" required>
                                        <option value="">select</option>
                                        <option value="1">All Students</option>
                                        <option value="2">Individual</option>
                                        <option value="3">Course Wise</option>
                                    </select>
                                </div>
                                <div class="form-group" id="student">
                                    <label for="">Select Student</label>
                                    <select name="student" class="form-control">
                                        <option value="">select</option>
                                        @foreach ($user as $u_item)
                                            <option value="{{ $u_item->id }}">{{ $u_item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group" id="course">
                                    <label for="">Select Course</label>
                                    <select name="course" class="form-control">
                                        <option value="">select</option>
                                        @foreach ($courses as $c_item)
                                            <option value="{{ $c_item->id }}">{{ $c_item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Notification Title</label>
                                    <input type="text" class="form-control" name="name"
                                        placeholder="Enter notification title">
                                </div>

                                <div class="form-group">
                                    <label for="name">Notification</label>
                                    <textarea type="text" class="form-control" id="name" placeholder="Notification details" name="details"></textarea>
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-2"></div>

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
        $("#student").hide();
        $("#course").hide();

        $("#type").on('change', function() {
            var type = $("#type").val();
            if (type == 1) {
                $("#student").hide();
                $("#course").hide();
            } else if (type == 2) {
                $("#student").show();
                $("#course").hide();
            } else {
                $("#student").hide();
                $("#course").show();
            }
        });
    </script>
@endsection
