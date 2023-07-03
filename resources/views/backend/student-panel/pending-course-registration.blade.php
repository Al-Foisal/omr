@extends('backend.layouts.master')
@section('title', 'Pending course list')

@section('backend')
    <!-- Content Header (Course Exam Subject header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Pending course list</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Pending course list</li>
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
                                        <th>Course Name</th>
                                        <th>User Details</th>
                                        <th>Order ID</th>
                                        <th>Created_at</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        <tr>
                                            <td class="d-flex justify-content-around">

                                                <form action="{{ route('admin.studentPanel.updateStatus', $item) }}"
                                                    method="post">
                                                    @csrf
                                                    <button type="submit"
                                                        onclick="return(confirm('Are you sure want to approve this item?'))"
                                                        class="btn btn-success btn-xs">
                                                        {{ 'Approve' }}
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.studentPanel.delete', $item->id) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit"
                                                        onclick="return(confirm('Are you sure want to delete this item?'))"
                                                        class="btn btn-danger btn-xs">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                            <td>{{ $item->course->name??'' }}</td>
                                            <td>
                                                <strong>Name: </strong>{{ $item->user->name }} <br>
                                                <strong>Email: </strong>{{ $item->user->email }} <br>
                                                <strong>Phone: </strong>{{ $item->user->phone }} <br>
                                            </td>
                                            <td>
                                                {{ $item->order_id }}
                                            </td>
                                            <td>{{ $item->created_at->format('d F, Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $data->links() }}
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
