<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
</head>
<body>
    <form action="{{ route('sys-admin.validate') }}" method="POST">
        @csrf
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" placeholder="Enter valid email" value="{{ old('email') }}" required>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" placeholder="Enter password" required>
        <button type="submit">Login</button>
    </form>
</body>
</html>