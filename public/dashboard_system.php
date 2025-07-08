<?php
session_start();
require '../includes/db_system.php'; 

if (!isset($_SESSION['user_id'])) {
    header('Location: login_system.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'] ?? 'Utente';
$user_name = ucwords(strtolower($user_name));


try {
    $stmt = $pdo->prepare("SELECT * FROM bills WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $fatture = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Errore DB: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - Delizia Caffè</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #fdf6f0;
      margin: 0;
      padding: 0;
    }

    header {
      text-align: center;
      padding: 20px;
    }

    header img {
      height: 120px;
    }

    .top-right {
      position: absolute;
      top: 20px;
      right: 20px;
      display: flex;
      gap: 15px;
    }

    .top-right a {
      color: #333;
      font-size: 20px;
      text-decoration: none;
    }

    main {
      text-align: center;
      padding: 30px;
    }

    h1 {
      color: #006400;
      margin-bottom: 10px;
    }

    p.subtitle {
      font-size: 18px;
      color: #444;
    }

    .chart-container {
      width: 90%;
      max-width: 800px;
      margin: 40px auto;
    }

    .no-invoice {
      font-size: 18px;
      color: #666;
      margin-top: 40px;
    }

    .btn-buy {
      background-color: #006400;
      color: white;
      padding: 15px 25px;
      font-size: 18px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      text-decoration: none;
      margin-top: 30px;
      display: inline-block;
    }

    .btn-buy:hover {
      background-color: #004d00;
    }
  </style>
</head>
<body>

<header>
  <img src="logotransparent.png" alt="Delizia Caffè">
  <div class="top-right">
    <a href="#"><i class="fas fa-envelope"></i></a>
    <a href="logout_system.php"><i class="fas fa-sign-out-alt"></i></a>
  </div>
</header>

<main>
  <h1>Benvenuto, <?php echo htmlspecialchars($user_name); ?>!</h1>
  <p class="subtitle">Questa è la tua pagina riservata del Delizia Caffè Club.</p>

  <?php if (!empty($mesi) && !empty($totali)): ?>
    <div class="chart-container">
      <canvas id="fattureChart"></canvas>
    </div>
  <?php else: ?>
    <p class="no-invoice">Nessuna fattura disponibile.</p>
  <?php endif; ?>

  <a class="btn-buy" href="#">Compra Adesso</a>
</main>

<?php if (!empty($mesi) && !empty($totali)): ?>
  <script>
    const ctx = document.getElementById('fattureChart').getContext('2d');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: <?php echo json_encode($mesi); ?>,
        datasets: [{
          label: 'Fatture',
          data: <?php echo json_encode($totali); ?>,
          borderColor: '#006400',
          backgroundColor: 'rgba(0, 100, 0, 0.1)',
          pointBackgroundColor: '#006400',
          fill: true,
          tension: 0.3
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  </script>
<?php endif; ?>
</body>
</html>



