<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/security.php';
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../admin.php');
}

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    flash('error', 'Vous devez vous connecter pour effectuer cette action.');
    redirect('../admin.php');
}

$member_id = isset($_POST['member_id']) ? (int) $_POST['member_id'] : 0;
$action = $_POST['action'] ?? null;
$allowed_actions = ['valider', 'refuser'];

if ($member_id <= 0 || !in_array($action, $allowed_actions, true)) {
    flash('error', 'Action invalide.');
    redirect('../admin.php');
}

try {
    $pdo = Database::getInstance();
    $new_status = ($action === 'valider') ? 'valide' : 'refuse';

    $stmt = $pdo->prepare("UPDATE membres SET statut = ? WHERE id = ?");
    $stmt->execute([$new_status, $member_id]);

    $message = $action === 'valider'
        ? "Inscription validée avec succès."
        : "Inscription refusée avec succès.";
    flash('success', $message);
} catch (Throwable $e) {
    flash('error', "Impossible de mettre à jour l'inscription.");
}

redirect('../admin.php');