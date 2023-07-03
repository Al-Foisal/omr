@php
    $user = App\Models\User::findOrFail(request()->id);
@endphp

<div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
    <h3 class="text-primary"><img src="{{ asset($user->image) }}" style="height:100px;border-radius: 55%;">
        {{ $user->name }}</h3>

    <br>
    <div class="text-muted">
        <p class="text-sm">Email
            <b class="d-block">{{ $user->email }}</b>
        </p>
        <p class="text-sm">Phone
            <b class="d-block">{{ $user->phone }}</b>
        </p>
        <p class="text-sm">Registration ID
            <b class="d-block">{{ $user->registration_id }}</b>
        </p>
        <p class="text-sm">Status
            <b class="d-block">{{ $user->status == 1 ? 'Active' : 'Inactive' }}</b>
        </p>
    </div>

    <div class="text-center mt-5 mb-3 d-flex justify-content-start">
        <form action="{{ route('admin.auth.updateStatus', $user) }}" method="post">
            @csrf
            <button type="submit" onclick="return(confirm('Are you sure?'))"
                class="btn btn-{{ $user->status == 1 ? 'danger' : 'success' }} btn-xs mr-1">
                {{ $user->status == 1 ? 'Inactive' : 'Active' }}
            </button>
        </form>
        <form action="{{ route('admin.auth.studentDelete', $user) }}" method="post">
            @csrf
            @method('delete')
            <button type="submit" onclick="return(confirm('Are you sure?'))" class="btn btn-danger btn-xs">
                {{ 'Delete' }}
            </button>
        </form>
    </div>
</div>
