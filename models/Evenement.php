<?php
/**
 * models/Evenement.php
 * Modèle — Gestion du calendrier des courses et régates
 */

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

class Evenement
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // ─────────────────────────────────────────────────────────────────────
    // LECTURE
    // ─────────────────────────────────────────────────────────────────────

    /**
     * Retourne tous les événements triés par date.
     *
     * @return array<int,array<string,mixed>>
     */
    public function findAll(): array
    {
        $stmt = $this->db->query("
            SELECT *, 
                   ROW_NUMBER() OVER (ORDER BY date_debut) AS numero
            FROM evenements
            ORDER BY date_debut ASC
        ");
        return $stmt->fetchAll();
    }

    /**
     * Retourne les prochains événements (à venir uniquement).
     *
     * @param int $limit  Nombre maximum d'événements à retourner
     * @return array<int,array<string,mixed>>
     */
    public function findUpcoming(int $limit = 3): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM evenements
            WHERE date_debut >= CURDATE()
              AND statut != 'annule'
            ORDER BY date_debut ASC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Retourne un événement par son ID.
     *
     * @return array<string,mixed>|false
     */
    public function findById(int $id): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM evenements WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // ─────────────────────────────────────────────────────────────────────
    // CRÉATION
    // ─────────────────────────────────────────────────────────────────────

    /**
     * Insère un nouvel événement.
     *
     * @param array<string,mixed> $data
     * @return int  ID de l'événement créé
     */
    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO evenements
                (nom_epreuve, date_debut, date_fin, heure_depart,
                 parcours, distance_mn, categorie, statut, description, places_max)
            VALUES
                (:nom_epreuve, :date_debut, :date_fin, :heure_depart,
                 :parcours, :distance_mn, :categorie, :statut, :description, :places_max)
        ");
        $stmt->execute([
            ':nom_epreuve'  => $data['nom_epreuve'],
            ':date_debut'   => $data['date_debut'],
            ':date_fin'     => $data['date_fin']    ?? null,
            ':heure_depart' => $data['heure_depart'],
            ':parcours'     => $data['parcours'],
            ':distance_mn'  => $data['distance_mn'] ?? null,
            ':categorie'    => $data['categorie'],
            ':statut'       => $data['statut']      ?? 'bientot',
            ':description'  => $data['description'] ?? null,
            ':places_max'   => $data['places_max']  ?? null,
        ]);
        return (int) $this->db->lastInsertId();
    }

    // ─────────────────────────────────────────────────────────────────────
    // MISE À JOUR
    // ─────────────────────────────────────────────────────────────────────

    /**
     * Met à jour un événement existant.
     *
     * @param int                 $id
     * @param array<string,mixed> $data
     */
    public function update(int $id, array $data): bool
    {
        $allowed_statuts = ['ouvert', 'complet', 'bientot', 'annule'];
        if (!in_array($data['statut'] ?? '', $allowed_statuts, true)) {
            $data['statut'] = 'bientot';
        }

        $stmt = $this->db->prepare("
            UPDATE evenements SET
                nom_epreuve  = :nom_epreuve,
                date_debut   = :date_debut,
                date_fin     = :date_fin,
                heure_depart = :heure_depart,
                parcours     = :parcours,
                distance_mn  = :distance_mn,
                categorie    = :categorie,
                statut       = :statut,
                description  = :description,
                places_max   = :places_max,
                updated_at   = NOW()
            WHERE id = :id
        ");
        return $stmt->execute([
            ':nom_epreuve'  => $data['nom_epreuve'],
            ':date_debut'   => $data['date_debut'],
            ':date_fin'     => $data['date_fin']    ?? null,
            ':heure_depart' => $data['heure_depart'],
            ':parcours'     => $data['parcours'],
            ':distance_mn'  => $data['distance_mn'] ?? null,
            ':categorie'    => $data['categorie'],
            ':statut'       => $data['statut'],
            ':description'  => $data['description'] ?? null,
            ':places_max'   => $data['places_max']  ?? null,
            ':id'           => $id,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────
    // SUPPRESSION
    // ─────────────────────────────────────────────────────────────────────

    /**
     * Supprime un événement (et ses inscriptions liées, via CASCADE).
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM evenements WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // ─────────────────────────────────────────────────────────────────────
    // HELPERS
    // ─────────────────────────────────────────────────────────────────────

    /**
     * Formate la date en français (ex: "15 Mars 2025").
     */
    public static function formatDate(string $date): string
    {
        $mois = [
            '01'=>'Janvier','02'=>'Février','03'=>'Mars','04'=>'Avril',
            '05'=>'Mai','06'=>'Juin','07'=>'Juillet','08'=>'Août',
            '09'=>'Septembre','10'=>'Octobre','11'=>'Novembre','12'=>'Décembre',
        ];
        [$y, $m, $d] = explode('-', $date);
        return ltrim($d, '0') . ' ' . $mois[$m] . ' ' . $y;
    }

    /**
     * Retourne les classes CSS et labels pour les badges de statut.
     *
     * @return array{class:string, label:string}
     */
    public static function badgeInfo(string $statut): array
    {
        return match($statut) {
            'ouvert'  => ['class' => 'badge-ouvert',  'label' => 'Ouvert'],
            'complet' => ['class' => 'badge-complet', 'label' => 'Complet'],
            'annule'  => ['class' => 'badge-complet', 'label' => 'Annulé'],
            default   => ['class' => 'badge-bientot', 'label' => 'Bientôt'],
        };
    }
}
