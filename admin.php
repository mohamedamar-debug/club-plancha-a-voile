<?php
declare(strict_types=1);

require_once __DIR__ . '/config/security.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Inscription.php';

// Vérification simple : mot de passe admin
$admin_password = 'admin123';
$is_authenticated = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
    if ($_POST['password'] === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
        $is_authenticated = true;
    } else {
        $error_message = "Mot de passe incorrect";
    }
}

// Si on ferme la session
if (isset($_GET['logout'])) {
    unset($_SESSION['admin_logged_in']);
    redirect('admin.php');
}

// Vérifier si connecté
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    $is_authenticated = true;
}

$page_title = 'Admin - Inscriptions';
$current_page = 'admin';

require __DIR__ . '/views/layout/header.php';
?>

<main style="max-width: 1000px; margin: 2rem auto; padding: 0 1rem;">
    <h1>Tableau de bord Admin</h1>

    <?php if (!$is_authenticated): ?>
        <!-- FORMULAIRE DE CONNEXION -->
        <div style="background: var(--color-background-secondary); padding: 2rem; border-radius: var(--border-radius-lg); max-width: 400px; margin: 2rem auto;">
            <h2 style="margin-top: 0;">Connexion Admin</h2>
            
            <?php if (isset($error_message)): ?>
                <p style="color: var(--color-text-danger); background: var(--color-background-danger); padding: 0.75rem; border-radius: var(--border-radius-md);">
                    <?= htmlspecialchars($error_message) ?>
                </p>
            <?php endif; ?>

            <form method="POST">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Mot de passe admin</label>
                <input type="password" name="password" required style="width: 100%; padding: 0.75rem; border: 0.5px solid var(--color-border-tertiary); border-radius: var(--border-radius-md); margin-bottom: 1rem;">
                <button type="submit" class="btn btn-primary">Se connecter</button>
            </form>
        </div>

    <?php else: ?>
        <!-- CONTENU ADMIN -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h2 style="margin: 0;">Inscriptions en attente</h2>
            <a href="?logout=1" style="color: var(--color-text-danger); text-decoration: underline;">Déconnexion</a>
        </div>

        <?php
        // Récupérer les inscriptions en attente
        $members = [];
        $admin_error = null;

        try {
            $pdo = Database::getInstance();
            $stmt = $pdo->query("SELECT * FROM membres WHERE statut = 'en_attente' ORDER BY created_at DESC");
            $members = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            $admin_error = "Erreur lors du chargement des inscriptions.";
        }
        ?>

        <?php if ($admin_error !== null): ?>
            <p style="color: var(--color-text-danger); background: var(--color-background-danger); padding: 0.75rem; border-radius: var(--border-radius-md);">
                <?= h($admin_error) ?>
            </p>
        <?php elseif (empty($members)): ?>
            <p style="color: var(--color-text-secondary); text-align: center; padding: 2rem;">
                Aucune inscription en attente. ✓
            </p>
        <?php else: ?>
            <table style="width: 100%; border-collapse: collapse; background: var(--color-background-primary); border: 0.5px solid var(--color-border-tertiary); border-radius: var(--border-radius-lg); overflow: hidden;">
                <thead>
                    <tr style="background: var(--color-background-secondary); border-bottom: 0.5px solid var(--color-border-tertiary);">
                        <th style="padding: 1rem; text-align: left; font-weight: 500;">Nom</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 500;">Email</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 500;">Niveau</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 500;">Date inscription</th>
                        <th style="padding: 1rem; text-align: center; font-weight: 500;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($members as $member): ?>
                        <tr style="border-bottom: 0.5px solid var(--color-border-tertiary);">
                            <td style="padding: 1rem;"><?= h($member['prenom'] . ' ' . $member['nom']) ?></td>
                            <td style="padding: 1rem;"><?= h($member['email']) ?></td>
                            <td style="padding: 1rem;">
                                <span style="background: var(--color-background-info); color: var(--color-text-info); padding: 0.25rem 0.75rem; border-radius: 4px; font-size: 12px;">
                                    <?= h($member['niveau_navigation']) ?>
                                </span>
                            </td>
                            <td style="padding: 1rem; color: var(--color-text-secondary); font-size: 13px;">
                                <?= date('d/m/Y', strtotime($member['created_at'])) ?>
                            </td>
                            <td style="padding: 1rem; text-align: center;">
                                <form method="POST" action="controllers/AdminController.php" style="display: inline;">
                                    <input type="hidden" name="member_id" value="<?= $member['id'] ?>">
                                    <button type="submit" name="action" value="valider" style="background: var(--color-background-success); color: var(--color-text-success); border: 0.5px solid var(--color-border-success); padding: 0.5rem 1rem; border-radius: 4px; cursor: pointer; margin-right: 0.5rem;">✓ Valider</button>
                                    <button type="submit" name="action" value="refuser" style="background: var(--color-background-danger); color: var(--color-text-danger); border: 0.5px solid var(--color-border-danger); padding: 0.5rem 1rem; border-radius: 4px; cursor: pointer;">✗ Refuser</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <p style="color: var(--color-text-secondary); text-align: center; margin-top: 1rem; font-size: 13px;">
                Total : <?= count($members) ?> inscription<?= count($members) > 1 ? 's' : '' ?> en attente
            </p>
        <?php endif; ?>
    <?php endif; ?>
</main>

<?php require __DIR__ . '/views/layout/footer.php'; ?>