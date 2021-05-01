<?php


class Farmaco
{
    private $minsan;
    private $nomeProdotto;
    private $prezzo;
    private $descrizione;
    private $img;
    private $linkSito;

    public function __construct($minsan, $nomeProdotto, $prezzo, $descrizione, $img, $linkSito)
    {
        $this->minsan = $minsan;
        $this->nomeProdotto = $nomeProdotto;
        $this->prezzo = $prezzo;
        $this->descrizione = $descrizione;
        $this->img = $img;
        $this->linkSito = $linkSito;
    }

    public function getMinsan()
    {
        return $this->minsan;
    }
    public function setMinsan($minsan)
    {
        $this->minsan = $minsan;
    }

    public function getNomeProdotto()
    {
        return $this->nomeProdotto;
    }
    public function setNomeProdotto($nomeProdotto)
    {
        $this->nomeProdotto = $nomeProdotto;
    }

    public function getPrezzo()
    {
        return $this->prezzo;
    }
    public function setPrezzo($prezzo)
    {
        $this->prezzo = $prezzo;
    }

    public function getDescrizione()
    {
        return $this->descrizione;
    }
    public function setDescrizione($descrizione)
    {
        $this->descrizione = $descrizione;
    }

    public function getImg()
    {
        return $this->img;
    }
    public function setImg($img)
    {
        $this->img = $img;
    }

    public function getLinkSito()
    {
        return $this->linkSito;
    }
    public function setLinkSito($linkSito)
    {
        $this->linkSito = $linkSito;
    }
}