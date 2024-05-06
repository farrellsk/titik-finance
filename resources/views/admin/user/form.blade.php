@php
if (isset($object)) {
    $viewData = [
        'title' => 'Edit User',
        'breadcrumbs' => [
            'Users',
            $object->email,
            'Edit',
        ],
    ];
} else {
    $viewData = [
        'title' => 'Add User',
        'breadcrumbs' => [
            'User',
            'Add',
        ]
    ];
}
@endphp

@extends('layouts.app', $viewData)

@section('content')
{{-- Form Start --}}
@php
if (isset($object)) {
    $actionUrl = route('users.update', $object->id);
} else {
    $actionUrl = route('users.store');
}
@endphp
<div class="row">
    <div class="{{ isset($object) ? "col-md-8" : "col-md-12" }}">
        <form action="{{ $actionUrl }}" method="POST" enctype="multipart/form-data">

            @if (isset($object))
            {{ method_field('PATCH') }}
            <input type="hidden" name="user_id" value="{{ $object->id }}" />
            @endif

            {{ csrf_field() }}

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ $viewData['title'] }}</h4>
                </div>
                <br>
                <div class="card-body">


                    <div class="form-body">
                        <div class="row">

                            <div class="col-12">
                                <div class="form-group row">
                                    <div class="col-md-2">
                                        <span>Nama</span>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="position-relative has-icon-left">
                                            <input type="text" class="form-control" name="name"
                                                value="{{ isset($object) ? $object->name : old('name') }}" placeholder="Name"
                                                autofocus>
                                            <div class="form-control-position">
                                                <i class="feather icon-user"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group row">
                                    <div class="col-md-2">
                                        <span>Email</span>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="position-relative has-icon-left">
                                            <input type="email" class="form-control" name="email"
                                                value="{{ isset($object) ? $object->email : old('email') }}"
                                                placeholder="Email">
                                            <div class="form-control-position">
                                                <i class="feather icon-mail"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


            <div class="col-12">
                <div class="form-group row">
                    <div class="col-md-2">
                        <span>Password</span>
                    </div>
                    <div class="col-md-10">
                        <div class="position-relative has-icon-left">
                            <input type="password" class="form-control" name="password" placeholder="Password">
                            <div class="form-control-position">
                                <i class="feather icon-lock"></i>
                            </div>
                        </div>
                        @if (isset($object))
                        <small class="text-info">abaikan jika tidak ingin mengubah password.</small>
                        @endif
                    </div>
                </div>
            </div>


            <div class="col-12">
                <div class="form-group row">
                    <div class="col-md-2">
                        <span>Konfirmasi Password</span>
                    </div>
                    <div class="col-md-10">
                        <div class="position-relative has-icon-left">
                            <input type="password" class="form-control" name="password_confirmation"
                                placeholder="Password Confirmation">
                            <div class="form-control-position">
                                <i class="feather icon-lock"></i>
                            </div>
                        </div>
                        @if (isset($object))
                        <small class="text-info">abaikan jika tidak ingin mengubah password.</small>
                        @endif
                    </div>
                </div>
            </div>
            @if (isset($object))
            <div class="col-12">
                <div class="form-group row">
                    <div class="col-md-2">
                        <span>Foto</span>
                    </div>
                    <div class="col-md-10">
                                <input id="photo" type="file" class="form-control" name="photo">
                                @if (isset($object))
                                <small class="text-info">abaikan jika tidak ingin mengubah foto.</small>
                                @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div class="col-md-2"></div>
            <div class="col-md-10">
                <button type="submit" class="btn btn-primary mr-1 mb-1 waves-effect waves-light">Save</button>
            </div>
            </div>
            </div>
            </div>
            </div>
        </form>

    {{-- Detail User --}}
    @if (isset($object))
    <div class="col-md-4">
            <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">User Detail</h4>
                    </div>
                    <br>

                    <div class="card-body">
                        <div class="text-center">
                            @if (Auth::user()->photo == null)
                            <img src="{{ asset('image/user.jpg') }}" style="max-width: 50%;" class="rounded-circle img-border box-shadow-1">
                            @else
                            <img src="{{ asset('storage/photos/' . Auth::user()->photo) }}" style="max-width: 50%;" class="rounded-circle img-border box-shadow-1">
                            @endif
                        </div>
                        <br>

                        @php
                        $details = [
                            'name' => 'Nama',
                            'email' => 'Email',
                        ];
                        @endphp

                        @foreach ($details as $key => $label)
                        <div class="mt-1">
                            <h6 class="mb-0">{{ $label }}:</h6>
                            <p>{{ !is_null($object->$key) ? $object->$key : '-' }}</p>
                        </div>
                        @endforeach

                    </div>
                </div>
        </div>
    @endif
    {{-- Detail User --}}
</div>

@endsection

@push('after_scripts')
<script>
    function checkUserType() {
        if ($("input[name=type]:checked").val() == "public") {
            $("#subsribe-charge").hide();
        } else {
            $("#subsribe-charge").show();
        }
    }
    checkUserType();

    $("input[name=type]").on("change", function () {
        checkUserType();
    });

</script>
@endpush
