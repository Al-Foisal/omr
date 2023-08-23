@php
    $user = App\Models\User::findOrFail(request()->id);
@endphp

<div class="col-12 col-md-4 col-lg-3 order-1 order-md-2">
    <style>
        .profile-image {
            max-width: 118px;
            max-height: 118px;
            text-align: center;
            margin: auto;
        }

        .profile-image>img {
            height: 118px;
            width: 118px;
            border-radius: 50%;
        }

        .bc {
            color: black
        }

        .section-details {
            float: left;
        }
    </style>
    <div class="card">
        <div class="card-body">
            <div class="inner-heading text-center">
                <div class="profile-image">
                    <img src="{{ asset($user->image) }}" alt="">
                </div>
                <h4 class="mt-4 bc">{{ $user->name }}</h4>
                <p class="text-md text-muted">Registration Id: <strong
                        class="bc">{{ $user->registration_id }}</strong></p>
            </div>

            <div class="card-inner-body p-4">
                <div class="d-flex justify-content-start">
                    <div class="section-icon mr-3">
                        <img src="{{ asset('email-icon.png') }}" alt="">
                    </div>
                    <div class="section-details">
                        <span>Email</span> <br>
                        <strong>{{ $user->email }}</strong>
                    </div>
                </div>
                <div class="d-flex justify-content-start mt-3">
                    <div class="section-icon mr-3">
                        <img src="{{ asset('phone-icon.png') }}" alt="">
                    </div>
                    <div class="section-details">
                        <span>Phone</span> <br>
                        <strong>{{ $user->phone }}</strong>
                    </div>
                </div>
            </div>
            <div class="card-footer-button p-4">
                <div class="d-flex justify-content-between">
                    <form action="{{ route('admin.auth.updateStatus', $user) }}" method="post">
                        @csrf
                        <button type="submit" onclick="return(confirm('Are you sure?'))"
                            class="btn btn-outline-{{ $user->status == 1 ? 'danger' : 'success' }} mr-1">
                            {{ $user->status == 1 ? 'Inactive' : 'Active' }}
                        </button>
                    </form>
                    <form action="{{ route('admin.auth.studentDelete', $user) }}" method="post">
                        @csrf
                        @method('delete')
                        <button type="submit" onclick="return(confirm('Are you sure?'))" class="btn btn-danger">
                            {{ 'Delete' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
