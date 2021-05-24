<?php

class Ordine
{
    private $idUtente;
    private $minsan;
    private $tipoOrdine; // 0 -> like / 1 -> carrello / 2 -> ordinato

    public function __construct($idUtente, $minsan, $tipoOrdine)
    {
        $this->idUtente = $idUtente;
        $this->minsan = $minsan;
        $this->tipoOrdine = $tipoOrdine;
    }

    public function getIdUtente()
    {
        return $this->idUtente;
    }
    public function setIdUtente($idUtente)
    {
        $this->idUtente = $idUtente;
    }

    public function getMinsan()
    {
        return $this->minsan;
    }
    public function setMinsan($minsan)
    {
        $this->minsan = $minsan;
    }

    public function getTipoOrdine()
    {
        return $this->tipoOrdine;
    }
    public function setTipoOrdine($tipoOrdine)
    {
        $this->tipoOrdine = $tipoOrdine;
    }
}
