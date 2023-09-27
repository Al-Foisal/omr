@extends('backend.layouts.master')
@section('title', 'Dashboard')

@section('backend')
    <!-- Content Header (Page header) -->
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


    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-6 offset-3">
                    <div class="card">
                        <div class="card-header">
                            <h3>Send notification to single user</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.fcmnotification_send') }}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label for="user">User</label>
                                    <select name="user_id" id="user" class="form-control">
                                        @php
                                            $user_list = \App\Models\User::get();
                                        @endphp
                                        @foreach($user_list as $item)
                                            <option value="{{ $item->id }}">{{ $item->email }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" name="title" id="title" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="body">Body</label>
                                    <textarea name="body" id="body" cols="30" class="form-control" rows="3"></textarea>
                                </div>

                                <button class="btn btn-primary" type="submit">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-6 offset-3">
                    <div class="card">
                        <div class="card-header">
                            <h3>Send notification to all user</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.fcmnotification_send_all') }}" method="post">
                                @csrf

                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" name="title" id="title" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="body">Body</label>
                                    <textarea name="body" id="body" cols="30" class="form-control" rows="3"></textarea>
                                </div>

                                <button class="btn btn-primary" type="submit">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
