@extends('backend.layouts.master')
@section('title', 'Notification List')

@section('backend')
    <!-- Content Header (Notification header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Notification</h1>
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
                <div class="col-12">
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body">
                            <a href="{{ route('admin.notification.create') }}" class="btn btn-outline-primary">Add
                                Notification</a>
                            <br>
                            <br>
                            <table id="" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Notification Title</th>
                                        <th>Notification Details</th>
                                        <th>Type</th>
                                        <th>Student Name</th>
                                        <th>Course Name</th>
                                        <th>Created_at</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        <tr>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->details }}</td>
                                            <td>{{ $item->types }}</td>
                                            <td>{{ $item->user->name ?? '-' }}</td>
                                            <td>{{ $item->course->name ?? '-' }}</td>
                                            <td>{{ $item->created_at->format('d F, Y') }}</td>
                                            <td>
                                                <form action="{{ route('admin.notification.delete', $item->id) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="btn btn-danger btn-xs">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
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
