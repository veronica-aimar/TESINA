<?php
include('Connection.php');

class ManagerFarmaco
{
    public static function create($farmaco)
    {
        $conn = Connection::connect();
        $sql = 'INSERT INTO tabella_farmaci VALUES('
            . $farmaco->getMinsan()
            . ',"' . $farmaco->getNomeProdotto()
            . '",' . $farmaco->getPrezzo()
            . ',' . $farmaco->getprezzoVecchio()
            . ',"' . $farmaco->getDescrizione()
            . '","' . $farmaco->getImg()
            . '","' . $farmaco->getCategoria() . '");';
        echo $sql;
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

        $ritorno = false;
        if($rs != false) {
            $ritorno = new Farmaco($rs['minsan'], $rs['nomeProdotto'], $rs['prezzo'], $rs['prezzoVecchio'], $rs['descrizione'], $rs['img'], $rs['categoria']);
        }
        
        $conn = null;
        return $ritorno;
    }

    public static function readAll($filtro = '')
    {
        $conn = Connection::connect();

        $where = '';
        if ($filtro != '') {
            $where .= ' WHERE nomeProdotto LIKE "%' . $filtro . '%" OR categoria LIKE "%' . $filtro . '%"';
        }

        $sql = "SELECT * FROM tabella_farmaci" . $where;
        $rs = $conn->query($sql)->fetchAll();

        $lista_prodotti = [];
        foreach($rs as $item) {
            $farmaco = new Farmaco($item['minsan'], $item['nomeProdotto'], $item['prezzo'], $item['prezzoVecchio'], $item['descrizione'], $item['img'], $item['categoria']);
            $lista_prodotti[] = $farmaco;
        }

        $conn = null;
        return $lista_prodotti;
    }

    public static function update($farmaco)
    {
        $conn = Connection::connect();
        $sql = 'UPDATE tabella_farmaci SET nomeProdotto="' . $farmaco->getNomeProdotto()
            . '", prezzo=' . $farmaco->getPrezzo()
            . ', prezzoVecchio=' . $farmaco->getprezzoVecchio()
            . ', descrizione="' . $farmaco->getDescrizione()
            . '", img="' . $farmaco->getImg()
            . '", categoria="' . $farmaco->getCategoria() . '" WHERE minsan=' . $farmaco->getMinsan() . ';';

        $conn->exec($sql);
        $conn = null;
    }

    // Non utilizzata
    public static function delete($minsan)
    {
        $conn = Connection::connect();
        $sql = "DELETE FROM tabella_farmaci WHERE minsan=" . $minsan . ";";

        $conn->exec($sql);
        $conn = null;
    }
}
