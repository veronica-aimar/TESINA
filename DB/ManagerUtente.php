<?php
include ('Connection.php');

class DBUtente
{
    public static function create($farmaco)
    {
        $conn = Connection::connect();
        $sql = "INSERT INTO tabella_utenti VALUES(NULL,"
            . ", " . $farmaco->getNome()
            . ", " . $farmaco->getCognome()
            . ", " . $farmaco->getTelefono()
            . ", " . $farmaco->getEmail()
            . ", " . $farmaco->getPassword() . ");";

        $conn->exec($sql);
        $minsan = $conn->lastInsertId();
        $conn = null;
        return $minsan;
    }

    public static function readById($id)
    {
        $conn = Connection::connect();
        $sql = "SELECT * FROM tabella_utenti WHERE id=" . $id . ";";
        $rs = $conn->query($sql)->fetch();

        $farmaco = new Farmaco($rs['id'], $rs['nome'], $rs['cognome'], $rs['telefono'], $rs['email'], $rs['password']);
        $conn = null;
        return $farmaco;
    }

    public static function readAll()
    {
        $conn = Connection::connect();
        $sql = "SELECT * FROM tabella_utenti;";
        $rs = $conn->query($sql)->fetchAll();

        $conn = null;
        return $rs;
    }

    public static function update($farmaco)
    {
        $conn = Connection::connect();
        $sql = "UPDATE tabella_utenti SET nome=" . $farmaco->getNome()
            . ", cognome=" . $farmaco->getCognome()
            . ", telefono=" . $farmaco->getTelefono()
            . ", email=" . $farmaco->getEmail()
            . ", password=" . $farmaco->getPassword()
            . " WHERE id=" . $farmaco->getId() . ";";

        $conn->exec($sql);
        $conn = null;
    }

    public static function delete($id)
    {
        $conn = Connection::connect();
        $sql = "DELETE FROM tabella_utenti WHERE id=" . $id . ";";

        $conn->exec($sql);
        $conn = null;
    }
}