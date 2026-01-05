<?php
session_start();

/* ===== DB CONNECTION ===== */
$host = "127.0.0.1";
$port = "308"; // مهم
$db   = "can2025";
$user = "root";
$pass = "";
 

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die($e->getMessage()); // باش نشوفو الخطأ الحقيقي
}

/* ===== LOGIN LOGIC ===== */
$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $stmt = $pdo->prepare(
        "SELECT * FROM users WHERE email = ? LIMIT 1"
    );
    $stmt->execute([$email]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    // ⚠️ Plain text password (comme DB actuelle)
    if ($userData && $password === $userData["password"]) {

        $_SESSION["user_id"] = $userData["id"];
        $_SESSION["first_name"] = $userData["first_name"];
        $_SESSION["last_name"] = $userData["last_name"];

        header("Location: accuil.php");
        exit;

    } else {
        $error = "Email ou mot de passe incorrect";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | CAF Morocco 25</title>
    <link rel="stylesheet" href="pgcnx.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<div class="container">

    <!-- LEFT -->
    <div class="left"></div>

    <!-- RIGHT -->
    <div class="right">
        <h1>Sign In</h1>

        <?php if($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="email" name="email" placeholder="Email address" required>
            <input type="password" name="password" placeholder="Password" required>

            <button type="submit">Login</button>

            <p class="register">
                Don’t have an account? <a href="accuil.php">Sign Up</a>
            </p>
        </form>
    </div>

</div>

</body>
</html>
