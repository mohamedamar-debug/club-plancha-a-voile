<?php
/**
 * controllers/CalendrierController.php
 * Contrôleur — Affichage et gestion du calendrier des courses
 */

declare(strict_types=1);

require_once __DIR__ . '/../config/security.php';
require_once __DIR__ . '/../models/Evenement.php';

class CalendrierController
{
    private Evenement $model;

    public function __construct()
    {
        $this->model = new Evenement();
    }

    /**
     * Affiche la page calendrier avec tous les événements depuis la BDD.
     */
    public function index(): void
    {
        $evenements = $this->model->findAll();
        $flashes    = get_flash();
        require __DIR__ . '/../views/calendrier/index.php';
    }
}
