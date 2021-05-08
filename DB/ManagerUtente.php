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
            . ", " . $farmaco->getUsername()
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

        $farmaco = new Farmaco($rs['id'], $rs['nome'], $rs['cognome'], $rs['telefono'], $rs['email'], $rs['username'], $rs['password']);
        $conn = null;
        return $farmaco;
    }

    public static function readAll($filtro = '')
    {
        $conn = Connection::connect();

        $where = '';
        if ($filtro != '') {
            $where .= " WHERE username LIKE '%" . $filtro . "%'" . "OR nome LIKE %" . $filtro . "% OR cognome LIKE %" . $filtro . "%;";
        }

        $sql = "SELECT * FROM tabella_utenti " . $where;
        $rs = $conn->query($sql)->fetchAll();

        $lista_utenti = [];
        foreach($rs as $item) {
            $utente = new Utente($item['id'], $item['nome'], $item['cognome'], $item['telefono'], $item['email'], $item['username'], $item['password']);
            $lista_utenti[] = $utente;
        }

        $conn = null;
        return $lista_utenti;
    }

    public static function update($farmaco)
    {
        $conn = Connection::connect();
        $sql = "UPDATE tabella_utenti SET nome=" . $farmaco->getNome()
            . ", cognome=" . $farmaco->getCognome()
            . ", telefono=" . $farmaco->getTelefono()
            . ", email=" . $farmaco->getEmail()
            . ", username=" . $farmaco->getUsername()
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