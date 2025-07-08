<?php
session_start();
require '../includes/db_system.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Successo login
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];

            header('Location: dashboard_system.php');
            exit;
        } else {
            $error = 'Email o password non validi.';
        }
    } else {
        $error = 'Inserisci tutti i campi.';
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login - Delizia Caffè Club</title>
  <style>
    body { 
      font-family: 'Segoe UI', sans-serif;
      background: #fffaf5;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
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
      width: 80%;
      padding: 12px;
      background-color: #006400;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 1em;
      cursor: pointer;
    }

    button:hover {
      background-color: #004d00;
    }

    .message {
      margin-top: 15px;
      color: red;
      font-weight: bold;
    }

    .register-link {
      margin-top: 20px;
      font-size: 0.9em;
      color: #006400;
    }

    .register-link a {
      color: #006400;
      text-decoration: underline;
    }
  </style>
</head>

<body>
  <div class="logo">
    <img src="logotransparent.png" alt="Delizia Caffè Logo" />
  </div>

  <div class="login-box">
    <h2>Accedi alla tua Area Riservata</h2>

    <?php if (!empty($error)): ?>
      <p class="message"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="">
      <input type="email" name="email" placeholder="Email" required><br>
      <input type="password" name="password" placeholder="Password" required><br>
      <button type="submit">Accedi</button>
    </form>

    <div class="register-link">
      Non hai un account? <a href="register_system.php">Registrati qui</a>
    </div>
  </div>
</body>
</html>

