<?php
class Database extends PDO {

    private $error;

    public function __construct($host="localhost", $db="backbone", $username="backbone", $password="d6q4GNq2dQa8h4pq") {
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );

        try {
            parent::__construct('mysql:host='.$host.';dbname='.$db, $username, $password);
        } catch (PDOException $e) {
            //$this->error = $e->getMessage();
            echo $e->getMessage();
        }
    }
}
?>