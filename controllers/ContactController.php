<?php
/**
 * controllers/ContactController.php
 * Contrôleur — Gestion du formulaire d'inscription
 *
 * SÉCURITÉ :
 *  - Validation stricte côté serveur (ne jamais faire confiance au JS)
 *  - Nettoyage de toutes les entrées via clean()
 *  - Requêtes préparées dans le modèle → pas d'injection SQL
 *  - Protection anti-spam par honeypot (champ caché)
 */

declare(strict_types=1);

require_once __DIR__ . '/../config/security.php';
require_once __DIR__ . '/../models/Inscription.php';

class ContactController
{
    private Inscription $model;

    /** Erreurs de validation (champ → message) */
    private array $errors = [];

    /** Données nettoyées à repasser à la vue en cas d'erreur */
    private array $old = [];

    public function __construct()
    {
        $this->model = new Inscription();
    }

    // ─────────────────────────────────────────────────────────────────────
    // AFFICHAGE DU FORMULAIRE (GET)
    // ─────────────────────────────────────────────────────────────────────

    public function showForm(): void
    {
        $flashes = get_flash();
        require __DIR__ . '/../views/contact/form.php';
    }

    // ─────────────────────────────────────────────────────────────────────
    // TRAITEMENT DU FORMULAIRE (POST)
    // ─────────────────────────────────────────────────────────────────────

    public function handleForm(): void
    {
    
        //  Anti-spam honeypot : le champ "website" doit rester vide
        if (!empty($_POST['website'])) {
            // Faux succès pour ne pas alerter les bots
            flash('success', '✅ Votre demande a bien été enregistrée. Nous vous contacterons sous 48h.');
            redirect('contact.php');
        }

        // 3. Collecte et nettoyage des données POST
        $this->old = $this->collectData();

        // 4. Validation serveur
        $this->validate($this->old);

        // 5. Si erreurs → on réaffiche le formulaire avec les messages
        if (!empty($this->errors)) {
            $errors = $this->errors;
            $old    = $this->old;
            $flashes = get_flash();
            require __DIR__ . '/../views/contact/form.php';
            return;
        }

        // 6. Vérification doublon email
        if ($this->model->emailExists($this->old['email'])) {
            $this->errors['email'] = 'Cette adresse e-mail est déjà inscrite dans notre base.';
            $errors  = $this->errors;
            $old     = $this->old;
            $flashes = get_flash();
            require __DIR__ . '/../views/contact/form.php';
            return;
        }

        // 7. Insertion en base de données
        try {
            $id = $this->model->create($this->old);
            flash('success', "✅ Demande d'inscription enregistrée (réf. #{$id}). Nous vous contacterons sous 48h.");
            redirect('contact.php');
        } catch (PDOException $e) {
            error_log('Erreur inscription : ' . $e->getMessage());
            flash('error', '❌ Une erreur technique est produite. Veuillez réessayer ou nous contacter par téléphone.');
            redirect('contact.php');
        }
    }

    // ─────────────────────────────────────────────────────────────────────
    // COLLECTE DES DONNÉES POST
    // ─────────────────────────────────────────────────────────────────────

    private function collectData(): array
    {
        // Checkboxes → tableaux filtrés
        $activites     = $_POST['activites'] ?? [];
        $disponibilites = $_POST['dispo']    ?? [];

        // Whitelist des valeurs autorisées pour les checkboxes
        $allowed_activites = ['courses','formation','construction','plongee','culture','benevole'];
        $allowed_dispos    = ['semaine','weekend','vacances','flexible'];

        $activites      = array_filter($activites,     fn($v) => in_array($v, $allowed_activites, true));
        $disponibilites = array_filter($disponibilites,fn($v) => in_array($v, $allowed_dispos, true));

        return [
            'prenom'          => clean(post('prenom')       ?? ''),
            'nom'             => clean(post('nom')          ?? ''),
            'email'           => clean(post('email')        ?? ''),
            'telephone'       => clean(post('telephone')    ?? ''),
            'naissance'       => clean(post('naissance')    ?? ''),
            'nationalite'     => clean(post('nationalite')  ?? ''),
            'genre'           => clean(post('genre')        ?? ''),
            'niveau'          => clean(post('niveau')       ?? ''),
            'activites'       => array_values($activites),
            'disponibilites'  => array_values($disponibilites),
            'message'         => clean(post('message')      ?? ''),
            'source_info'     => clean(post('comment')      ?? ''),
            'newsletter'      => isset($_POST['newsletter']) ? 1 : 0,
            'reglement'       => isset($_POST['reglement']),
        ];
    }

    // ─────────────────────────────────────────────────────────────────────
    // VALIDATION SERVEUR
    // ─────────────────────────────────────────────────────────────────────

    private function validate(array $data): void
    {
        // Prénom
        if (empty($data['prenom'])) {
            $this->errors['prenom'] = 'Le prénom est obligatoire.';
        } elseif (mb_strlen($data['prenom']) < 2 || mb_strlen($data['prenom']) > 80) {
            $this->errors['prenom'] = 'Le prénom doit contenir entre 2 et 80 caractères.';
        }

        // Nom
        if (empty($data['nom'])) {
            $this->errors['nom'] = 'Le nom est obligatoire.';
        } elseif (mb_strlen($data['nom']) < 2 || mb_strlen($data['nom']) > 80) {
            $this->errors['nom'] = 'Le nom doit contenir entre 2 et 80 caractères.';
        }

        // Email
        if (empty($data['email'])) {
            $this->errors['email'] = 'L\'adresse e-mail est obligatoire.';
        } elseif (!valid_email($data['email'])) {
            $this->errors['email'] = 'Veuillez saisir une adresse e-mail valide.';
        }

        // Téléphone (facultatif mais vérifié si renseigné)
        if (!empty($data['telephone']) && !valid_phone($data['telephone'])) {
            $this->errors['telephone'] = 'Format de téléphone invalide (ex : 74 123 456).';
        }

        // Date de naissance
        if (empty($data['naissance'])) {
            $this->errors['naissance'] = 'La date de naissance est obligatoire.';
        } else {
            $birth = \DateTime::createFromFormat('Y-m-d', $data['naissance']);
            $today = new \DateTime();
            if (!$birth || $birth > $today) {
                $this->errors['naissance'] = 'Veuillez saisir une date de naissance valide.';
            } elseif ($birth->diff($today)->y < 7) {
                $this->errors['naissance'] = 'L\'âge minimum pour adhérer est de 7 ans.';
            } elseif ($birth->diff($today)->y > 120) {
                $this->errors['naissance'] = 'Veuillez saisir une date de naissance valide.';
            }
        }

        // Genre
        $genres_allowed = ['homme', 'femme', 'autre'];
        if (empty($data['genre']) || !in_array($data['genre'], $genres_allowed, true)) {
            $this->errors['genre'] = 'Veuillez sélectionner votre genre.';
        }

        // Niveau de navigation
        $niveaux_allowed = ['debutant', 'intermediaire', 'confirme', 'expert'];
        if (empty($data['niveau']) || !in_array($data['niveau'], $niveaux_allowed, true)) {
            $this->errors['niveau'] = 'Veuillez sélectionner votre niveau de navigation.';
        }

        // Activités (au moins une obligatoire)
        if (empty($data['activites'])) {
            $this->errors['activites'] = 'Veuillez sélectionner au moins une activité.';
        }

        // Règlement intérieur (obligatoire)
        if (!$data['reglement']) {
            $this->errors['reglement'] = 'Vous devez accepter le règlement intérieur pour vous inscrire.';
        }
    }
}
