<?php
session_start();
require '../includes/db_system.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = $_POST['name'] ?? '';
    $email    = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($name) && !empty($email) && !empty($password)) {
        // Check if user already exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = 'Email is already registered.';
        } else {
            // Hash password
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            // Insert user
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $hashed]);

            $success = 'Registration successful! You can now <a href="login_system.php">log in</a>.';
        }
    } else {
        $error = 'All fields are required.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register</title>
  
</head>
<body>
<div class="logo">
    <img src="logotransparent.png" alt="Delizia Caffè Logo" />
  </div>
<h2>Registrati al Club</h2>

<?php if ($error): ?>
  <p class="error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if ($success): ?>
  <p class="success"><?= $success ?></p>
<?php endif; ?>

<form method="POST">
  <input type="text" name="name" placeholder="Full Name" required>
  <input type="email" name="email" placeholder="Email" required>
  <input type="password" name="password" placeholder="Password" required>
  <button type="submit">Register</button>
</form>

<p>Already have an account? <a href="login_system.php">Log in here</a></p>
<style>
 body { 
      font-family: 'Segoe UI', sans-serif;
      background: #fffaf5;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }

    .logo img {
      height: 100px;
    }

    .login-box {
      background-color: #f3ede7;
      padding: 40px;
      border-radius: 15px;
      text-align: center;
      width: 100%;
      max-width: 350px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    }

    input {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 1em;
    }

    button {
      width: 103%;
      padding: 12px;
      background-color: #006400;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 1em;
      cursor: pointer;
    }

    button:hover {
      background-color: #00640091;
    }

p {
  text-align: center;
  margin-top: 10px;
}

a {
  color: #007bff;
  text-decoration: none;
}

a:hover {
  text-decoration: underline;
}

.error {
  color: red;
  text-align: center;
  margin-top: 20px;
}

.success {
  color: green;
  text-align: center;
  margin-top: 20px;
}
</style>
</body>
</html>

