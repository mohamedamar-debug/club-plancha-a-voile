<?php
/**
 * contact.php — Front Controller pour la page Contact
 */

declare(strict_types=1);

require_once __DIR__ . '/config/security.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Inscription.php';
require_once __DIR__ . '/controllers/ContactController.php';

$controller = new ContactController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->handleForm();
} else {
    $controller->showForm();
}