<?php
include('Connection.php');

class ManagerOrdiniUtente
{
    public static function create($ordineUtente)
    {
        $conn = Connection::connect();
        $sql = "INSERT INTO tabella_ordini VALUES("
            . $ordineUtente->getIdUtente()
            . ", " . $ordineUtente->getMinsan()
            . ", " . $ordineUtente->getTipoOrdine(). ");";

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

        $ordiniUtente = new Ordine($rs['idUtente'], $rs['idFarmaco']);
        $conn = null;
        return $ordiniUtente;
    }

    public static function update($ordineUtente)
    {
        $conn = Connection::connect();
        $sql = "UPDATE tabella_utenti SET minsan=" . $ordineUtente->getMinsan()
            . ", tipoOrdine=" . $ordineUtente->getTipoOrdine()
            . " WHERE idUtente=" . $ordineUtente->getIdUtente() . ";";

        $conn->exec($sql);
        $conn = null;
    }

    public static function delete($idUtente, $idFarmaco)
    {
        $conn = Connection::connect();
        $sql = "DELETE FROM tabella_ordini WHERE idUtente=" . $idUtente . " AND idFarmaco=" . $idFarmaco . ";";

        $conn->exec($sql);
        $conn = null;
    }
}
