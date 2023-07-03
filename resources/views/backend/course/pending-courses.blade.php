@extends('backend.layouts.master')
@section('title', 'Pending Course List')

@section('backend')
    <!-- Content Header (Course header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Pending Courses</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Course</li>
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
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>Course Name</th>
                                        <th>Subject Name</th>
                                        <th>Details</th>
                                        <th>Purchase Link</th>
                                        <th>Enrolled</th>
                                        <th>Created_at</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        <tr>
                                            <td class="d-flex justify-content-around">
                                                <a href="{{ route('admin.course.createOrEdit', $item) }}"
                                                    class="btn btn-info btn-xs"> <i class="fas fa-edit"></i> Edit</a>

                                                <form action="{{ route('admin.course.updateStatus', $item) }}"
                                                    method="post">
                                                    @csrf
                                                    <button type="submit" onclick="return(confirm('Are you sure?'))"
                                                        class="btn btn-{{ $item->status == 1 ? 'danger' : 'success' }} btn-xs">
                                                        {{ $item->status == 1 ? 'Inactive' : 'Active' }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td>{{ $item->name }}</td>
                                            <td>
                                                @foreach ($item->subjects as $sd)
                                                    <strong>Subject: </strong> {{ $sd->name }}, <br>
                                                    <div class="pl-3">
                                                        @foreach ($sd->exams as $e)
                                                            <strong>Exam: </strong>{{ $e->name }} <br>
                                                            <div class="pl-3">
                                                                @foreach ($e->topics as $t)
                                                                    <strong>Topic: </strong>{{ $t->name }}, <br>
                                                                @endforeach
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endforeach
                                            </td>
                                            <td>{{ Str::limit(strip_tags($item->details), 50) ?? 'Not set yet' }}</td>
                                            <td>{{ $item->purchase_link ?? 'Not set yet' }}</td>
                                            <td>{{ $item->enrolled_count }}</td>
                                            <td>{{ $item->created_at->format('d F, Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{-- {{ $data->links() }} --}}
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
