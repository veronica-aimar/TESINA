<?php

class Ordine
{
    private $idUtente;
    private $minsan;
    private $tipoOrdine; // 0 -> like / 1 -> carrello / 2 -> ordinato
    private $quantita;

    public function __construct($idUtente, $minsan, $tipoOrdine, $quantita)
    {
        $this->idUtente = $idUtente;
        $this->minsan = $minsan;
        $this->tipoOrdine = $tipoOrdine;
        $this->quantita = $quantita;
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

    public function getQuantita()
    {
        return $this->quantita;
    }
    public function setQuantita($quantita)
    {
        $this->quantita = $quantita;
    }
}
