<!-- IRAKIZA Jean Boneur    25/31038 -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register here</title>
    <link rel="stylesheet" type="text/css" href="assets/auth.css">
</head>
<body>

    <h2 class="auth-heading">Register</h2>
    <form method="POST" action="auth.php">
        <input class="input-field" type="text" name="name" placeholder="Full Name" required>
        <input class="input-field" type="email" name="email" placeholder="School Email" required>
        <input class="input-field" type="password" name="password" placeholder="Password" required>
        <button class="btn-action" type="submit" name="register">Register</button>
    </form>
</body>
</html>