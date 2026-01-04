<?php
session_start();

include "config.php";

$allStades = $pdo->query("SELECT * FROM stades ORDER BY id ASC")->fetchAll();

if (!$allStades) {
    die("Aucun stade trouvé dans la base !");
}

$stadeId = isset($_GET['stade']) ? (int)$_GET['stade'] : $allStades[0]['id'];

$currentIndex = 0;
foreach ($allStades as $i => $s) {
    if ($s['id'] == $stadeId) {
        $currentIndex = $i;
        break;
    }
}

$stade = $allStades[$currentIndex];

$prevIndex = ($currentIndex - 1 + count($allStades)) % count($allStades);
$nextIndex = ($currentIndex + 1) % count($allStades);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Achat Ticket</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .arrow { font-size: 30px; margin: 0 20px; text-decoration: none; padding: 5px 12px;font-size: 20px; color: white; font-weight: bold; }
        .center-arrow { text-align: center; margin: 10px; }
    </style>
</head>
<body>

<header>
    <h2>LOGO</h2>
    <div class="divider"></div>
</header>

<h2><?= htmlspecialchars($stade['nom']) ?></h2>

<img src="assets/images/<?= htmlspecialchars($stade['image']) ?>" width="100%" class="center-img" alt="<?= htmlspecialchars($stade['nom']) ?>"> <br>

<div class="center-arrow">
    <a href="acheter_ticket.php?stade=<?= $allStades[$prevIndex]['id'] ?>" class="arrow">&#8592; Précédent</a>
    <a href="acheter_ticket.php?stade=<?= $allStades[$nextIndex]['id'] ?>" class="arrow">Suivant &#8594;</a>
</div>

<form method="POST" action="PagePayment.php"><br>
    <div class="form-row">
        <select name="zone" required>
            <option class="option" value="">Sélectionner votre zone</option>
            <option value="VIP">VIP</option>
            <option value="Tribune">Tribune</option>
            <option value="Virage">Virage</option>
        </select>

        <br><br>

        <label>
            <input type="radio" name="quantite" value="1" checked> 1 Ticket
        </label>
        <label>
            <input type="radio" name="quantite" value="2"> 2 Tickets
        </label>
    </div>
    
<br>
    <button type="submit" class="btn-acheter">Acheter Maintenant</button>
</form>

</body>
</html>
