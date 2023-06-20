@extends('backend.layouts.master')
@section('title', 'Course Exam Subject List')

@section('backend')
    <!-- Content Header (Course Exam Subject header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Course Exam Subject</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Course Exam Subject</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body">
                            <a href="{{ route('admin.examQuestion.createOrEdit') }}" class="btn btn-outline-primary">Add
                                Course Exam Subject</a>
                            <br>
                            <br>
                            <table id="" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>Course Name</th>
                                        <th>Subject Name</th>
                                        <th>Exam Details</th>
                                        <th>Statue</th>
                                        <th>Created_at</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $d_item)
                                        @foreach ($d_item->subject_details as $item)
                                            <tr>
                                                <td class="d-flex justify-content-around">
                                                    <a href="{{ route('admin.exam.createOrEdit', $item) }}"
                                                        class="btn btn-info btn-xs"> <i class="fas fa-edit"></i> Edit</a>

                                                    <form action="{{ route('admin.exam.updateStatus', $item) }}"
                                                        method="post">
                                                        @csrf
                                                        <button type="submit" onclick="return(confirm('Are you sure?'))"
                                                            class="btn btn-{{ $item->status == 1 ? 'danger' : 'success' }} btn-xs">
                                                            {{ $item->status == 1 ? 'Inactive' : 'Active' }}
                                                        </button>
                                                    </form>
                                                </td>
                                                <td>{{ $d_item->course->name }}</td>
                                                <td>
                                                    {{ $item->name }}
                                                </td>
                                                <td>
                                                    <b>Name:</b> {{ $d_item->name }} <br>
                                                    <b>Total question:</b> {{ $d_item->total_question }} <br>
                                                    <b>Per question mark:</b> {{ $d_item->per_question_positive_mark }} <br>
                                                    <b>Per question negative mark:</b>
                                                    {{ $d_item->per_question_negative_mark }} <br>
                                                </td>
                                                <td>{{ $d_item->status == 1 ? 'Active' : 'Inactive' }}</td>
                                                <td>{{ $d_item->created_at->format('d F, Y') }}</td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
