@extends('backend.layouts.master')
@section('title', 'User List')

@section('backend')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>User</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">User List</li>
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
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Registration ID</th>
                                        <th>Enrolled</th>
                                        <th>Image</th>
                                        <th>Created_at</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customers as $key => $customer)
                                        <tr>
                                            <td class="d-flex justify-content-around">
                                                <form action="{{ route('admin.auth.updateStatus', $customer) }}"
                                                    method="post">
                                                    @csrf
                                                    <button type="submit" onclick="return(confirm('Are you sure?'))"
                                                        class="btn btn-{{ $customer->status == 1 ? 'danger' : 'success' }} btn-xs">
                                                        {{ $customer->status == 1 ? 'Inactive' : 'Active' }}
                                                    </button>
                                                </form>
                                                <a href="{{ route('admin.auth.studentDetails', $customer) }}"
                                                    class="btn btn-xs btn-info">Student Info</a>
                                            </td>
                                            <td>{{ $customer->name }}</td>
                                            <td>{{ $customer->email }}</td>
                                            <td>{{ $customer->phone ?? '' }}</td>
                                            <td>{{ $customer->registration_id }}</td>
                                            <td>{{ $customer->courses_count }}</td>
                                            <td><img src="{{ asset($customer->image ?? 'profile.png') }}" height="50"
                                                    width="50" style="border-radius: 50%;"></td>
                                            <td>{{ $customer->created_at->format('d F, Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $customers->links() }}
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
