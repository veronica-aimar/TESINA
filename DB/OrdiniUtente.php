<?php

class OrdiniUtente
{
    private $idUtente;
    private $idFarmaco;

    public function __construct($idUtente, $idFarmaco)
    {
        $this->idUtente = $idUtente;
        $this->idFarmaco = $idFarmaco;
    }

    public function getIdUtente()
    {
        return $this->idUtente;
    }
    public function setIdUtente($idUtente)
    {
        $this->idUtente = $idUtente;
    }

    public function getIdFarmaco()
    {
        return $this->idFarmaco;
    }
    public function setIdFarmaco($idFarmaco)
    {
        $this->idFarmaco = $idFarmaco;
    }
}
