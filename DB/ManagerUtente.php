<?php
include('Connection.php');

class ManagerUtente
{
    public static function create($utente)
    {
        $conn = Connection::connect();

        // controllo se l'utente esiste già
        $sql = "SELECT * FROM tabella_utenti WHERE username='" . $utente->getUsername()
            . "' OR telefono=" . $utente->getTelefono()
            . " OR email='" . $utente->getEmail()
            . "' OR (nome='" . $utente->getNome() . "' AND cognome='" . $utente->getCognome() . "');";
        $rs = $conn->query($sql)->fetch();
        $id = -1;
        if ($rs == null) {
            $sql = "INSERT INTO tabella_utenti VALUES(NULL"
                . ", '" . $utente->getNome()
                . "', '" . $utente->getCognome()
                . "', " . $utente->getTelefono()
                . ", '" . $utente->getEmail()
                . "', '" . $utente->getUsername()
                . "', '" . $utente->getPassword() . "');";

            $conn->exec($sql);
            $id = $conn->lastInsertId();
        }
        $conn = null;
        return $id;
    }

    public static function readById($id)
    {
        $conn = Connection::connect();
        $sql = "SELECT * FROM tabella_utenti WHERE id=" . $id . ";";
        $rs = $conn->query($sql)->fetch();

        $utente = new Utente($rs['id'], $rs['nome'], $rs['cognome'], $rs['telefono'], $rs['email'], $rs['username'], $rs['password']);
        $conn = null;
        return $utente;
    }

    public static function readUser($username)
    {
        $conn = Connection::connect();

        $sql = 'SELECT * FROM tabella_utenti WHERE userName="'
            . $username . '" OR email="'
            . $username . '";';
        
        $rs = $conn->query($sql)->fetch();

        $conn = null;
        return $rs;
    }

    public static function readAll($filtro = '')
    {
        $conn = Connection::connect();

        $where = '';
        if ($filtro != '') {
            $where .= " WHERE username LIKE '%" . $filtro . "%'" . "OR nome LIKE' %" . $filtro . "%' OR cognome LIKE '%" . $filtro . "%';";
        }

        $sql = "SELECT * FROM tabella_utenti " . $where;
        $rs = $conn->query($sql)->fetchAll();

        $lista_utenti = [];
        foreach ($rs as $item) {
            $utente = new Utente($item['id'], $item['nome'], $item['cognome'], $item['telefono'], $item['email'], $item['username'], $item['password']);
            $lista_utenti[] = $utente;
        }

        $conn = null;
        return $lista_utenti;
    }

    public static function update($utente)
    {
        $conn = Connection::connect();
        $sql = "UPDATE tabella_utenti SET nome=" . $utente->getNome()
            . ", cognome=" . $utente->getCognome()
            . ", telefono=" . $utente->getTelefono()
            . ", email=" . $utente->getEmail()
            . ", username=" . $utente->getUsername()
            . ", password=" . $utente->getPassword()
            . " WHERE id=" . $utente->getId() . ";";

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
