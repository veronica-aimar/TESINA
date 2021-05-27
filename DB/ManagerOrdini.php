<?php
if(  !class_exists('Connection') ) {
    include('Connection.php');
}

if(  !class_exists('Ordine') ) {
    include('Ordine.php');
}

class ManagerOrdini
{
    public static function create($ordine)
    {
        $conn = Connection::connect();

        $sql = "SELECT * FROM tabella_ordini WHERE idUtente=" . $ordine->getIdUtente()
            . " AND minsan=" . $ordine->getMinsan()
            . " AND tipoOrdine=" . $ordine->getTipoOrdine() . ";";

        echo $sql;
        $rs = $conn->query($sql)->fetch();
        $id = -1;

        if ($rs == null) {
            $sql = "INSERT INTO tabella_ordini VALUES("
                . $ordine->getIdUtente()
                . ", " . $ordine->getMinsan()
                . ", " . $ordine->getTipoOrdine()
                . ", " . $ordine->getQuantita() . ");";

            echo $sql;
            $conn->exec($sql);
            $idUtente = $conn->lastInsertId();
        }

        $conn = null;
        return $id;
    }

    public static function readAll($idUtente, $tipoOrdine)
    {
        $conn = Connection::connect();

        $sql = "SELECT * FROM tabella_ordini WHERE idUtente=" . $idUtente . " AND tipoOrdine=" . $tipoOrdine . ";";
        $rs = $conn->query($sql)->fetchAll();

        $lista_ordini = [];
        foreach ($rs as $item) {
            $ordine = new Ordine($item['idUtente'], $item['minsan'], $item['tipoOrdine'], $item['quantita']);
            $lista_ordini[] = $ordine;
        }

        $conn = null;
        return $lista_ordini;
    }

    public static function delete($idUtente, $minsan)
    {
        $conn = Connection::connect();
        $sql = "DELETE FROM tabella_ordini WHERE idUtente=" . $idUtente . " AND minsan=" . $minsan . ";";

        $conn->exec($sql);
        $conn = null;
    }
}
