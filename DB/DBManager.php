<?php

class DBManager
{
    private static function connect()
    {
        $conn = new PDO("sqlite:Farmaci.db");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    }

    public static function create($farmaco)
    {
        $conn = self::connect();
        $sql = "INSERT INTO tabella_farmaci VALUES("
            . $farmaco->getMinsan()
            . ", " . $farmaco->getNomeProdotto()
            . ", " . $farmaco->getPrezzo()
            . ", " . $farmaco->getVecchioPrezzo()
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
        $conn = self::connect();
        $sql = "SELECT * FROM tabella_farmaci WHERE minsan=" . $minsan . ";";
        $rs = $conn->query($sql)->fetch();

        $farmaco = new Farmaco($rs['minsan'], $rs['nomeProdotto'], $rs['prezzo'], $rs['prezzoVecchio'], $rs['descrizione'], $rs['img'], $rs['linkSito'], $rs['categoria']);
        $conn = null;
        return $farmaco;
    }

    public static function readAll()
    {
        $conn = self::connect();
        $sql = "SELECT * FROM tabella_farmaci;";
        $rs = $conn->query($sql);

        $conn = null;
        return $rs->fetchAll();
    }

    public static function update($farmaco)
    {
        $conn = self::connect();
        $sql = "UPDATE tabella_farmaci SET nomeProdotto=" . $farmaco->getNomeProdotto()
            . ", prezzo=" . $farmaco->getPrezzo()
            . ", vecchioPrezzo=" . $farmaco->getVecchioPrezzo()
            . ", descrizione=" . $farmaco->getDescrizione()
            . ", img=" . $farmaco->getImg()
            . ", linkSito=" . $farmaco->getLinkSito()
            . ", categoria=" . $farmaco->getCategoria() . " WHERE minsan=" . $farmaco->getMinsan() . ";";

        $conn->exec($sql);
        $conn = null;
    }

    public static function delete($farmaco)
    {
        $conn = self::connect();
        $sql = "DELETE FROM tabella_farmaci WHERE minsan=" . $farmaco->getMinsan() . ";";

        $conn->exec($sql);
        $conn = null;
    }
}
