@extends('backend.layouts.master')
@section('title', 'Add Question')

@section('backend')
    <!-- Content Header (Course Exam Subject header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Add Question</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Question</li>
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
                            <table id="" class="table table-bordered table-striped">
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
                                    @foreach ($data as $item)
                                        <tr>
                                            <td class="d-flex justify-content-around">
                                                <a href="{{ route('admin.examQuestion.createOrEdit', $item->id) }}"
                                                    class="btn btn-info btn-xs">Add Question</a>

                                                <form action="{{ route('admin.exam.updateStatus', $item) }}" method="post">
                                                    @csrf
                                                    <button type="submit" onclick="return(confirm('Are you sure?'))"
                                                        class="btn btn-{{ $item->status == 1 ? 'danger' : 'success' }} btn-xs">
                                                        {{ $item->status == 1 ? 'Inactive' : 'Active' }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td>
                                                <b>Exam Name:</b> {{ $item->name }} <br>
                                                <b>Exam Code:</b> {{ $item->code }} <br>
                                                <b>Total question:</b> {{ $item->total_question }} <br>
                                                <b>Per question mark:</b> {{ $item->per_question_positive_mark }} <br>
                                                <b>Per question negative mark:</b>
                                                {{ $item->per_question_negative_mark }} <br>
                                            </td>
                                            <td>{{ $item->course->name }}</td>
                                            <td>
                                                <strong>Subject: </strong>{{ $item->subject->name }}, <br>
                                                <!--<b>Topic:</b> <br>-->
                                                <!--<div class="pl-3">-->
                                                <!--    @foreach ($item->topics as $topic)-->
                                                <!--        {{ $topic->name }}, <br>-->
                                                <!--    @endforeach-->
                                                <!--</div>-->
                                            </td>
                                            <td>{{ $item->status == 1 ? 'Active' : 'Inactive' }}</td>
                                            <td>{{ $item->created_at->format('d F, Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $data->links() }}
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
