<?php
include('Connection.php');

class ManagerOrdiniUtente
{
    private static function connect()
    {
        $conn = new PDO("sqlite:Farmaci.db");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    }

    public static function create($ordineUtente)
    {
        $conn = Connection::connect();
        $sql = "INSERT INTO tabella_ordini VALUES("
            . $farmaco->getIdUtente()
            . ", " . $farmaco->getIdFarmaco() . ");";

        $conn->exec($sql);
        $minsan = $conn->lastInsertId();
        $conn = null;
        return $minsan;
    }

    public static function readById($idUtente)
    {
        $conn = Connection::connect();
        $sql = "SELECT * FROM tabella_ordini WHERE idUtente=" . $idUtente . ";";
        $rs = $conn->query($sql)->fetch();

        $OrdiniUtente = new Ordine($rs['idUtente'], $rs['idFarmaco']);
        $conn = null;
        return $ordiniUtente;
    }

    public static function delete($idUtente, $idFarmaco)
    {
        $conn = Connection::connect();
        $sql = "DELETE FROM tabella_ordini WHERE idUtente=" . $idUtente . " AND idFarmaco=" . $idFarmaco . ";";

        $conn->exec($sql);
        $conn = null;
    }
}
