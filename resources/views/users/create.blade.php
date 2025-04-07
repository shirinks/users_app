@extends('layouts.app')

@section('title', 'Create User')

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
                    <h4 class="card-title">Create New User</h4>
                    
                    <!-- Form for creating a user -->
                    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data"id="userCreateForm">
                        @csrf
                        <div class="form-group">
                            <label for="name">First Name</label>
                            <input type="text" name="first_name" id="first_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="name">Last Name</label>
                            <input type="text" name="last_name" id="last_name" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="position">Position</label>
                            <input type="text" name="position" id="position" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="role">Role</label>
                            <select name="role" id="role" class="form-control" required>
                                <option value="">Select Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="photo">Profile Photo</label>
                            <input type="file" name="photo" id="photo" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-success">Create User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> 
<script>
    var $j = jQuery.noConflict();  // Use $j instead of $ to avoid conflicts
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

<script>
    $j(document).ready(function() {
        $j("#userCreateForm").validate({
            rules: {
                first_name: {
                    required: true
                },
                last_name: {
                    required: true
                },
                email: {
                    required: true,
                    email: true,
                    remote: {
                    url: "/check-email", 
                    type: "post",
                    data: {
                        email: function () {
                            return $("#email").val();
                        },
                        _token: '{{ csrf_token() }}'
                        }
                    }
                },
                position: {
                    required: true
                },
                role: {
                    required: true
                },
                password: { 
                    required: true, 
                    minlength: 8
                },
                password_confirmation: { 
                    required: true, 
                    minlength: 8, 
                    equalTo: "#password" 
                }
            },
            messages: {
                first_name: {
                    required: "Please enter your first name"
                },
                last_name: {
                    required: "Please enter your last name"
                },
                email: {
                    required: "Please enter your email address",
                    email: "Please enter a valid email address",
                    remote: "This email is already taken"
                },
                position: {
                    required: "Please enter the position"
                },
                role: {
                    required: "Please select a role"
                },
                password_confirmation: {
                    equalTo: "Should match with password"
                },
            },
            errorElement: "div",
            errorClass: "invalid-feedback", 
            highlight: function(element) {
                $(element).addClass("is-invalid"); 
            },
            unhighlight: function(element) {
                $(element).removeClass("is-invalid"); 
            },
            errorPlacement: function(error, element) {
                error.insertAfter(element); 
            }
        });
        $("input").on("keyup", function() {
            $j(this).valid(); 
        });
        $j("#userCreateForm").on("submit", function(event) {
            if ($j(this).valid()) {
                return true;
            } else {
                event.preventDefault();
                return false;
            }
        });
    });
</script>
@endsection


