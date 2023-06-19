@extends('backend.layouts.master')
@section('title', 'Subject Topic List')

@section('backend')
    <!-- Content Header (Subject Topic header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Subject Topic</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Subject Topic</li>
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
                            <a href="{{ route('admin.topic.createOrEdit') }}" class="btn btn-outline-primary">Add
                                Subject Topic</a>
                            <br>
                            <br>
                            <table id="" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>Name</th>
                                        <th>Subject Name</th>
                                        <th>Statue</th>
                                        <th>Created_at</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $d_item)
                                        @foreach ($d_item->subjectToics as $item)
                                            <tr>
                                                <td class="d-flex justify-content-around">
                                                    <a href="{{ route('admin.topic.createOrEdit', $item) }}"
                                                        class="btn btn-info btn-xs"> <i class="fas fa-edit"></i> Edit</a>

                                                    <form action="{{ route('admin.topic.updateStatus', $item) }}"
                                                        method="post">
                                                        @csrf
                                                        <button type="submit" onclick="return(confirm('Are you sure?'))"
                                                            class="btn btn-{{ $item->status == 1 ? 'danger' : 'success' }} btn-xs">
                                                            {{ $item->status == 1 ? 'Inactive' : 'Active' }}
                                                        </button>
                                                    </form>
                                                </td>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->subject->name }}</td>
                                                <td>{{ $item->status == 1 ? 'Active' : 'Inactive' }}</td>
                                                <td>{{ $item->created_at->format('d F, Y') }}</td>
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
