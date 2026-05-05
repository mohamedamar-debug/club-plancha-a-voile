<?php
class Database
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            // On a supprimé les variables host, port, user, pass pour les mettre direct ici
            $dsn = "mysql:host=localhost;dbname=felouques_kerkennah;charset=utf8mb4";
            
            // On garde seulement l'option pour voir les erreurs, c'est vital
            $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

            self::$instance = new PDO($dsn, 'root', '', $options);
        }
        return self::$instance;
    }
}