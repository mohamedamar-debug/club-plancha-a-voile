<?php
/**
 * models/Inscription.php
 * Modèle — Gestion des membres / inscriptions
 *
 * Toutes les requêtes utilisent des requêtes préparées PDO
 * → protection totale contre les injections SQL (OWASP A03).
 */

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

class Inscription
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // ─────────────────────────────────────────────────────────────────────
    // CRÉATION
    // ─────────────────────────────────────────────────────────────────────

    /**
     * Insère une nouvelle demande d'inscription.
     *
     * @param array<string,mixed> $data  Données validées du formulaire
     * @return int  ID du nouveau membre inséré
     * @throws PDOException  Si l'email existe déjà (UNIQUE key) ou autre erreur
     */
    public function create(array $data): int
    {
        $sql = "
            INSERT INTO membres
                (prenom, nom, email, telephone, date_naissance, nationalite,
                 genre, niveau_navigation, activites, disponibilites,
                 message, source_info, newsletter)
            VALUES
                (:prenom, :nom, :email, :telephone, :date_naissance, :nationalite,
                 :genre, :niveau_navigation, :activites, :disponibilites,
                 :message, :source_info, :newsletter)
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':prenom'             => $data['prenom'],
            ':nom'                => $data['nom'],
            ':email'              => strtolower($data['email']),
            ':telephone'          => $data['telephone']   ?? null,
            ':date_naissance'     => $data['naissance'],
            ':nationalite'        => $data['nationalite'] ?? null,
            ':genre'              => $data['genre'],
            ':niveau_navigation'  => $data['niveau'],
            // Tableaux PHP → JSON pour MySQL
            ':activites'          => json_encode($data['activites']    ?? []),
            ':disponibilites'     => json_encode($data['disponibilites'] ?? []),
            ':message'            => $data['message']    ?? null,
            ':source_info'        => $data['source_info'] ?? null,
            ':newsletter'         => (int)($data['newsletter'] ?? 0),
        ]);

        return (int) $this->db->lastInsertId();
    }

    // ─────────────────────────────────────────────────────────────────────
    // LECTURE
    // ─────────────────────────────────────────────────────────────────────

    /**
     * Retourne tous les membres (usage admin).
     *
     * @return array<int,array<string,mixed>>
     */
    public function findAll(): array
    {
        $stmt = $this->db->query("
            SELECT id, prenom, nom, email, telephone,
                   date_naissance, genre, niveau_navigation, statut, created_at
            FROM membres
            ORDER BY created_at DESC
        ");
        return $stmt->fetchAll();
    }

    /**
     * Retourne un membre par son ID.
     *
     * @return array<string,mixed>|false
     */
    public function findById(int $id): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM membres WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Vérifie si un email est déjà utilisé.
     */
    public function emailExists(string $email): bool
    {
        $stmt = $this->db->prepare("SELECT 1 FROM membres WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => strtolower($email)]);
        return (bool) $stmt->fetchColumn();
    }
    public function phoneExists(string $phone): bool
    {
    $sql = "SELECT COUNT(*) FROM membres WHERE telephone = :phone";
    $stmt = $this->db->prepare($sql);
    $stmt->execute(['phone' => $phone]);
    
    // Si le compte est supérieur à 0, le téléphone existe déjà
    return $stmt->fetchColumn() > 0;
    }

    // ─────────────────────────────────────────────────────────────────────
    // MISE À JOUR
    // ─────────────────────────────────────────────────────────────────────

    /**
     * Met à jour le statut d'un membre.
     *
     * @param int    $id      ID du membre
     * @param string $statut  'en_attente' | 'valide' | 'refuse'
     */
    public function updateStatut(int $id, string $statut): bool
    {
        $allowed = ['en_attente', 'valide', 'refuse'];
        if (!in_array($statut, $allowed, true)) {
            throw new \InvalidArgumentException("Statut invalide : $statut");
        }

        $stmt = $this->db->prepare("UPDATE membres SET statut = :statut WHERE id = :id");
        return $stmt->execute([':statut' => $statut, ':id' => $id]);
    }

    /**
     * Met à jour les informations d'un membre.
     *
     * @param int                 $id    ID du membre
     * @param array<string,mixed> $data  Champs à modifier
     */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE membres
            SET prenom           = :prenom,
                nom              = :nom,
                telephone        = :telephone,
                nationalite      = :nationalite,
                niveau_navigation= :niveau_navigation,
                message          = :message,
                newsletter       = :newsletter,
                updated_at       = NOW()
            WHERE id = :id
        ");
        return $stmt->execute([
            ':prenom'            => $data['prenom'],
            ':nom'               => $data['nom'],
            ':telephone'         => $data['telephone']    ?? null,
            ':nationalite'       => $data['nationalite']  ?? null,
            ':niveau_navigation' => $data['niveau_navigation'],
            ':message'           => $data['message']      ?? null,
            ':newsletter'        => (int)($data['newsletter'] ?? 0),
            ':id'                => $id,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────
    // SUPPRESSION
    // ─────────────────────────────────────────────────────────────────────

    /**
     * Supprime un membre par son ID.
     * La suppression en cascade est gérée par la FK dans `inscriptions_courses`.
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM membres WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
