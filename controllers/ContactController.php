<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/security.php'; // Pour les fonctions flash() et redirect()
require_once __DIR__ . '/../models/Inscription.php';

class ContactController
{
    private Inscription $model;

    public function __construct()
    {
        $this->model = new Inscription();
    }

    /** Affiche le formulaire (GET) */
    public function showForm(): void
    {
        $flashes = get_flash(); // Récupère le message de succès s'il existe
        require __DIR__ . '/../views/contact/form.php';
    }

    /** Traite les données envoyées (POST) */
    public function handleForm(): void
    {
        $data = $_POST;
        $errors = [];

        // --- TES VÉRIFICATIONS ---
        
        // 1. Email valide
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "L'adresse e-mail n'est pas correcte.";
        }

        // 2. Téléphone (exactement 8 chiffres)
        if (!preg_match('/^[0-9]{8}$/', $data['telephone'])) {
            $errors['telephone'] = "Le téléphone doit contenir exactement 8 chiffres.";
        }

        // --- GESTION DU RÉSULTAT ---

        if (!empty($errors)) {
            // S'il y a des erreurs, on réaffiche le formulaire avec les messages
            $flashes = get_flash();
            require __DIR__ . '/../views/contact/form.php';
        } else {
            // Si tout est OK, on enregistre
            try {
                $id = $this->model->create($data);
                
                // On prépare le message de succès (ton image)
                flash('success', "✅ Demande d'inscription enregistrée (réf. #{$id}). Nous vous contacterons sous 48h.");
                
                // On redirige pour afficher le message sur une page "propre"
                redirect('contact.php');
            } catch (Exception $e) {
                echo "Erreur lors de l'enregistrement : " . $e->getMessage();
            }
        }
    }
}