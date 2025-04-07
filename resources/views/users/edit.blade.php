@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-sucess">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    <h4 class="card-title">Edit User</h4>
                    @if($user->photo)
                            <img src="{{ asset('storage/images/' . $user->photo) }}" alt="User Photo" class="img-thumbnail" width="100" style="float:right;width: 100px; height: 100px; object-fit: cover;">
                    @else
                            <img src="{{ asset('storage/images/default_photo.jpg') }}" alt="Default Photo" class="img-thumbnail" width="100" style="float:right;width: 100px; height: 100px; object-fit: cover;">
                    @endif
                    <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data" id="userForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="user_id" value="{{ $user->id ?? '' }}">
                        <div class="form-group" style="margin-top:100px;">
                            <label for="first_name">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required>
                            @error('first_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required>
                            @error('last_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="position">Position</label>
                            <input type="text" class="form-control" id="position" name="position" value="{{ old('position', $user->position) }}" required>
                            @error('position')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="photo">Profile Photo</label>
                            <input type="file" class="form-control" id="photo" name="photo">
                            @error('photo')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="role">Role</label>
                            <select class="form-control" name="role" id="role" required>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
                                @endforeach
                            </select>
                            @error('role')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                      
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> 
<script>
    var $j = jQuery.noConflict();  
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

<script>
    $j(document).ready(function() {
    let $form = $j("#userForm");

    $form.validate({
        rules: {
            first_name: { required: true },
            last_name: { required: true },
            email: {
                required: true,
                email: true,
                remote: {
                    url: "/check-email",
                    type: "post",
                    data: {
                        email: function() { return $("#email").val(); },
                        user_id: function() { return $("#user_id").val(); },
                        _token: '{{ csrf_token() }}'
                    }
                }
            },
            position: { required: true },
            role: { required: true }
        },
        messages: {
            first_name: { required: "Please enter your first name" },
            last_name: { required: "Please enter your last name" },
            email: {
                required: "Please enter your email address",
                email: "Please enter a valid email address",
                remote: "This email is already taken"
            },
            position: { required: "Please enter the position" },
            role: { required: "Please select a role" }
        },
        errorElement: "div",
        errorClass: "invalid-feedback",
        highlight: function(element) {
            $j(element).addClass("is-invalid");
        },
        unhighlight: function(element) {
            $j(element).removeClass("is-invalid");
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            form.submit();
        },
        invalidHandler: function(event, validator) {
            alert("Please correct the errors before submitting.");
        }
    });

    $j("input, select").on("keyup change", function () {
        $j(this).valid();
    });
});

</script>
@endsection

