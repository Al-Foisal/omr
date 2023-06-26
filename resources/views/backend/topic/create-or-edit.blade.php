@extends('backend.layouts.master')
@section('title', request('id') ? 'Update' : 'Create' . ' Subject Topic')
@section('backend')
    <!-- Content Header (Subject Topic header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ request('id') ? 'Update' : 'Create' }} Subject Topic</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Create Subject Topic</li>
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
                            action="{{ request('id') ? route('admin.topic.storeOrUpdate', $data->id) : route('admin.topic.storeOrUpdate') }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            @if (request('id'))
                                @method('put')
                            @endif
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="">Select Subject</label>
                                    <select name="subject_id" id="" class="form-control" required>
                                        <option value="">select</option>
                                        @foreach ($subject as $item)
                                            <option value="{{ $item->id }}"
                                                @if ($data && $data->subject_id == $item->id) {{ 'selected' }} @endif>
                                                {{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="name">Topic*</label>
                                    <input type="text" class="form-control" id="name" placeholder="Name"
                                        name="name" value="{{ $data->name ?? '' }}">
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
