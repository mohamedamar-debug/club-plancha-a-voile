<?php
/**
 * calendrier.php — Front Controller pour la page Calendrier
 */

declare(strict_types=1);

require_once __DIR__ . '/config/security.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Evenement.php';
require_once __DIR__ . '/controllers/CalendrierController.php';

$controller = new CalendrierController();
$controller->index();