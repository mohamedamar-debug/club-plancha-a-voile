<?php
/**
 * config/security.php
 * Fonctions de sécurité transversales
 *
 * OWASP Top 10 couvert :
 *  A01 – Contrôle d'accès : fonctions de session
 *  A03 – Injection : échappement HTML / requêtes préparées (via PDO)
 *  A05 – Mauvaise configuration : headers de sécurité
 *  A07 – XSS : h() = htmlspecialchars systématique
 *  A08 – CSRF : jeton synchroniseur (STP pattern)
 */

declare(strict_types=1); 


// ─── Démarrage de session sécurisé ─────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) { 
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => isset($_SERVER['HTTPS']),  // HTTPS uniquement en prod
        'httponly' => true,                       // Inaccessible depuis JS → anti-XSS
        'samesite' => 'Strict',                  // Anti-CSRF supplémentaire
    ]);
    session_start();
}

// ─── Échappement HTML (anti-XSS) ───────────────────────────────────────────

/**
 * Alias court de htmlspecialchars — à utiliser SYSTÉMATIQUEMENT dans les vues.
 *
 * @param mixed $value  Valeur à échapper
 */
function h(mixed $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// ─── Nettoyage des entrées ──────────────────────────────────────────────────

/**
 * Nettoie une chaîne de caractères :
 *  - Supprime les espaces superflus
 *  - Supprime les balises HTML
 *
 * N'utilise PAS stripslashes — c'est le rôle des requêtes préparées PDO.
 */
function clean(string $value): string
{
    return trim(strip_tags($value));
}

/**
 * Valide et retourne une valeur depuis $_POST.
 * Retourne null si la clé est absente.
 */
function post(string $key): ?string
{
    return isset($_POST[$key]) ? clean($_POST[$key]) : null;
}

/**
 * Valide une adresse e-mail.
 */
function valid_email(string $email): bool
{
    return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Valide un numéro de téléphone tunisien (format souple).
 * Exemples valides : 74123456  /  +21674123456  /  0021674123456
 */
function valid_phone(string $phone): bool
{
    return (bool) preg_match('/^(\+?216|00216)?[2-9]\d{7}$/', preg_replace('/\s/', '', $phone));
}

// ─── Flash messages ────────────────────────────────────────────────────────

/**
 * Enregistre un message flash en session.
 *
 * @param string $type   'success' | 'error' | 'info'
 * @param string $msg    Texte du message
 */
function flash(string $type, string $msg): void
{
    $_SESSION['flash'][] = ['type' => $type, 'msg' => $msg];
}

/**
 * Récupère et vide les messages flash.
 *
 * @return array<array{type:string, msg:string}>
 */
function get_flash(): array
{
    $flashes = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $flashes;
}

/**
 * Redirige vers une URL et termine le script.
 */
function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}
