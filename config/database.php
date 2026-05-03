<?php

declare(strict_types=1);

class Database
{
    private static ?PDO $instance = null;

    private function __construct() {}

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            // Configuration XAMPP
            $host   = 'localhost';
            $port   = '3306';
            $dbname = 'felouques_kerkennah';
            $user   = 'root';
            $pass   = '';

            $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4' COLLATE 'utf8mb4_unicode_ci'",
            ];

            try {
                self::$instance = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                error_log('Erreur connexion BD : ' . $e->getMessage());
                http_response_code(503);
                die('<h2>❌ Erreur : Impossible de se connecter à la base de données</h2>
                     <p>Vérifiez que :</p>
                     <ul>
                       <li>✅ MySQL/MariaDB est démarré dans XAMPP</li>
                       <li>✅ La base "felouques_kerkennah" existe</li>
                       <li>✅ L\'utilisateur "root" a accès</li>
                     </ul>
                     <p><a href="http://localhost/phpmyadmin">Accéder à phpMyAdmin →</a></p>');
            }
        }

        return self::$instance;
    }

    private function __clone() {}
    public function __wakeup(): void {
        throw new Exception('Désérialisation interdite');
    }
}