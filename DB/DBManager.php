<?php


class DBManager
{
    private static function connect() {
        $conn = new PDO("mysql://localhost:3306/Farmaci");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    }

    public static function create($farmaco) {
        $conn = self::connect();
        $sql = "INSERT INTO table_farmaci VALUES(" . $farmaco->getMinsan()
            . ", " . $farmaco->getNomeProdotto()
            . ", " . $farmaco->getPrezzo()
            . ", " . $farmaco->getDescrizione()
            . ", " . $farmaco->getImg()
            . ", " . $farmaco->getLinkSito() . ");";

        $conn->exec($sql);
        $minsan = $conn->lastInsertId();
        $conn = null;
        return $minsan;
    }

    public static function readById($minsan) {
        $conn = self::connect();
        $sql = "SELECT * FROM table_farmaci WHERE minsan=" . $minsan . ";";
        $rs = $conn->query($sql)->fetch();

        $farmaco = new Farmaco(rs['minsan'], rs['nomeProdotto'], rs['prezzo'], rs['descrizione'], rs['img'], rs['linkSito']);
        $conn = null;
        return $farmaco;
    }

    public static function readAll() {
        $conn = self::connect();
        $sql = "SELECT * FROM table_farmaci;";
        $rs = $conn->query($sql)->fetchAll();

        $conn = null;
        return $rs;
    }

    public static function update($farmaco) {
        $conn = self::connect();
        $sql = "UPDATE table_farmaci SET nomeProdotto=" . $farmaco->getNomeProdotto()
            . ", prezzo=" . $farmaco->getPrezzo()
            . ", descrizione=" . $farmaco->getDescrizione()
            . ", img=" . $farmaco->getImg()
            . ", linkSito=" . $farmaco->getLinkSito() . " WHERE minsan=" . $farmaco->getMinsan() . ";";

        $conn->exec($sql);
        $conn = null;
    }

    public static function delete($farmaco) {
        $conn = self::connect();
        $sql = "DELETE FROM table_farmaci WHERE minsan=" . $farmaco->getMinsan() . ";";

        $conn->exec($sql);
        $conn = null;
    }
}