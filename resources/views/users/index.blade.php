@extends('layouts.app')

@section('title', 'User Accounts')

@section('content')
<div class="content-wrapper">
            <div class="row">
              <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="d-sm-flex align-items-center mb-4">
                      <h4 class="card-title mb-sm-0">User Accounts</h4>
                      <a href="{{ route('users.create') }}" class="btn btn-primary ml-auto">Create User</a>
                    </div>
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="table-responsive border rounded p-1">
                      <table class="table">
                        <thead>
                          <tr>
                            <th class="font-weight-bold">Name</th>
                            <th class="font-weight-bold">Email</th>
                            <th class="font-weight-bold">Position</th>
                            <th class="font-weight-bold">Role</th>
                            <th class="font-weight-bold">Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->full_name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->position }}</td>
                                    <td>{{ $user->getRoleNames()->first() }}</td>
                                    <td>
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm inline-block text-blue-500 hover:text-blue-700" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure to delete this user?')" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-block btn btn-danger btn-sm text-red-500 hover:text-red-700" title="Delete">
                                                <i class="fa fa-trash-o"></i>
                                            </button>

                                        </form>
                                        <a href="#" class="inline-block btn btn-warning btn-sm reset-password" data-id="{{ $user->id }}" data-name="{{ $user->full_name}}" data-toggle="modal" data-target="#resetPasswordModal">Reset Password</a>
                                    </td>  
                                </tr>
                            @endforeach
                        </tbody>
                      </table>
                    </div>
                    <div class="mt-4">
                        {{ $users->links('pagination::bootstrap-4') }}
                    </div>
                  </div>
                </div>
              </div>
            </div>   
</div>

<!-- Reset Password Modal -->
<!-- Reset Password Modal -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1" role="dialog" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form method="POST" id="resetPasswordForm">
        @csrf
        <input type="hidden" name="user_id" id="reset_user_id">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resetPasswordModalLabel">Reset Password</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <p>Reset password for <strong><span id="resetUserName"></span></strong></p>
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" name="password" id="new_password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                </div>
                <div id="confirmation_error" class="text-danger mb-2"></div>
                <div id="password_error" class="text-danger mb-2"></div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </form>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        // When the Reset Password button is clicked, set the user_id in the modal
        $('.reset-password').on('click', function() {
            var userId = $(this).data('id');
            var userName = $(this).data('name');
            $('#reset_user_id').val(userId);
            $('#resetUserName').text(userName);
            console.log(userId);
            console.log(userName);
           
            $('#resetPasswordModal').modal('show');
        });
        $('#resetPasswordModal').on('hidden.bs.modal', function () {
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open');
        });

        // Submit the password reset form
        $('#resetPasswordForm').on('submit', function(e) {
            e.preventDefault();
            alert();
            var userId = $('#reset_user_id').val();
            console.log(userId);
            var newPassword = $('#new_password').val();
            var confirmPassword = $('#password_confirmation').val();
            console.log(newPassword);
            console.log(confirmPassword);

            $('#confirmation_error').text('');

            if (newPassword.length < 8) {
                $('#new_password').addClass('is-invalid');
                $('#confirmation_error').text('Password must be at least 6 characters long.');
                return;
            }

            if (newPassword !== confirmPassword) {
                $('#confirm_password').addClass('is-invalid');
                $('#confirmation_error').text('Passwords do not match.');
                return;
            }

         
            $('#password_error').text('');
            $('#confirmation_error').text('');

            // AJAX request to reset password
            $.ajax({
                url: '{{ route('password.reset.ajax') }}',
                type: 'POST',
                data: {
                    user_id: userId,
                    password: newPassword,
                    password_confirmation: confirmPassword,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        $('#resetPasswordModal').modal('hide');
                        alert('Password reset successfully');
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                    } else {
                        $('#password_error').text(response.error);
                    }
                },
                error: function(xhr, status, error) {
                    $('#password_error').text('An error occurred. Please try again.');
                }
            });
        });
    });
</script>
@endsection
