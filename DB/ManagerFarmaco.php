<?php
include('Connection.php');

class DBFarmaco
{
    private static function connect()
    {
        $conn = new PDO("sqlite:Farmaci.db");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    }

    public static function create($farmaco)
    {
        $conn = Connection::connect();
        $sql = "INSERT INTO tabella_farmaci VALUES("
            . $farmaco->getMinsan()
            . ", " . $farmaco->getNomeProdotto()
            . ", " . $farmaco->getPrezzo()
            . ", " . $farmaco->getprezzoVecchio()
            . ", " . $farmaco->getDescrizione()
            . ", " . $farmaco->getImg()
            . ", " . $farmaco->getLinkSito()
            . ", " . $farmaco->getCategoria() . ");";

        $conn->exec($sql);
        $minsan = $conn->lastInsertId();
        $conn = null;
        return $minsan;
    }

    public static function readById($minsan)
    {
        $conn = Connection::connect();
        $sql = "SELECT * FROM tabella_farmaci WHERE minsan=" . $minsan . ";";
        $rs = $conn->query($sql)->fetch();

        $farmaco = new Farmaco($rs['minsan'], $rs['nomeProdotto'], $rs['prezzo'], $rs['prezzoVecchio'], $rs['descrizione'], $rs['img'], $rs['linkSito'], $rs['categoria']);
        $conn = null;
        return $farmaco;
    }

    public static function readAll($filtro = '')
    {
        $conn = Connection::connect();

        $where = '';
        if ($filtro != '') {
            $where .= " WHERE nomeProdotto LIKE '%" . $filtro . "%';";
        }

        $sql = "SELECT * FROM tabella_farmaci" . $where;
        $rs = $conn->query($sql)->fetchAll();

        $conn = null;
        return $rs;
    }

    public static function update($farmaco)
    {
        $conn = Connection::connect();
        $sql = "UPDATE tabella_farmaci SET nomeProdotto=" . $farmaco->getNomeProdotto()
            . ", prezzo=" . $farmaco->getPrezzo()
            . ", prezzoVecchio=" . $farmaco->getprezzoVecchio()
            . ", descrizione=" . $farmaco->getDescrizione()
            . ", img=" . $farmaco->getImg()
            . ", linkSito=" . $farmaco->getLinkSito()
            . ", categoria=" . $farmaco->getCategoria() . " WHERE minsan=" . $farmaco->getMinsan() . ";";

        $conn->exec($sql);
        $conn = null;
    }

    public static function delete($minsan)
    {
        $conn = Connection::connect();
        $sql = "DELETE FROM tabella_farmaci WHERE minsan=" . $minsan . ";";

        $conn->exec($sql);
        $conn = null;
    }
}
