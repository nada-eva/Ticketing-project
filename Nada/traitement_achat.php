<?php
session_start();
require 'config.php';

if (!isset($_POST['zone'], $_POST['quantite'])) {
    die("Données invalides");
}

$zone = $_POST['zone'];
$quantite = (int) $_POST['quantite'];

$prixZones = [
    "VIP" => 300,
    "Tribune" => 150,
    "Virage" => 80
];

if (!isset($prixZones[$zone])) {
    die("Zone invalide");
}

$prix_unitaire = $prixZones[$zone];
$total = $prix_unitaire * $quantite;

$stmt = $pdo->prepare("SELECT places_disponibles FROM zones WHERE nom = ?");
$stmt->execute([$zone]);
$zoneData = $stmt->fetch();

if (!$zoneData || $zoneData['places_disponibles'] < $quantite) {
    die("Match complet ou places insuffisantes");
}

$stmt = $pdo->prepare("
    INSERT INTO tickets (user_id, zone, quantite, total, date_achat)
    VALUES (?, ?, ?, ?, NOW())
");
$stmt->execute([
    $_SESSION['user_id'] ?? null,
    $zone,
    $quantite,
    $total
]);

$stmt = $pdo->prepare("
    UPDATE zones
    SET places_disponibles = places_disponibles - ?
    WHERE nom = ?
");
$stmt->execute([$quantite, $zone]);

header("Location: confirmation.php?total=$total");
exit;
