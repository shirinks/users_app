<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('web/css/style.css') }}">
</head>
<body>

<div class="container">
    <div class="login-btn">
        <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
    </div>
    <br>
    <h4>User Summary</h4>
    <table id="user-table">
        <thead>
            <tr>
                <th>Photo</th>
                <th>Name</th>
                <th>Email</th>
                <th>Position</th>
                <th>Role</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
        $(document).ready(function() {
            $.ajax({
                url: '/api/users',  
                type: 'GET',
                success: function(data) {
                    let tableBody = $('#user-table tbody');
                    data.forEach(function(user) {
                        let photo = user.photo ? 
                                    `<img src="{{ asset('/storage/images/${user.photo}') }}" alt="Photo" style="width: 50px; height: 50px; object-fit: cover;">` : 
                                    `<img src="{{ asset('/storage/images/default_photo.jpg') }}" alt="No Photo"style="width: 50px; height: 50px; object-fit: cover;">`;
                                    

                        let row = `
                            <tr>
                                <td>${photo}</td>
                                <td>${user.last_name} ${user.first_name}</td>
                                <td>${user.email}</td>
                                <td>${user.position || 'N/A'}</td>
                                <td>${user.roles.map(role => role.name).join(', ')}</td>
                            </tr>
                        `;

                        tableBody.append(row);
                    });
                },
                error: function() {
                    alert('Failed to fetch users.');
                }
            });
        });
    </script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
