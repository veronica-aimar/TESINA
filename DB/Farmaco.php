<?php

class Farmaco
{
    private $minsan;
    private $nomeProdotto;
    private $prezzo;
    private $prezzoVecchio;
    private $descrizione;
    private $img;
    private $categoria;

    public function __construct($minsan, $nomeProdotto, $prezzo, $prezzoVecchio, $descrizione, $img, $categoria)
    {
        $this->minsan = $minsan;
        $this->nomeProdotto = $nomeProdotto;
        $this->prezzo = $prezzo;
        $this->prezzoVecchio = $prezzoVecchio;
        $this->descrizione = $descrizione;
        $this->img = $img;
        $this->categoria = $categoria;
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

    public function getprezzoVecchio()
    {
        return $this->prezzoVecchio;
    }
    public function setprezzoVecchio($prezzoVecchio)
    {
        $this->prezzoVecchio = $prezzoVecchio;
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

    public function getCategoria()
    {
        return $this->categoria;
    }
    public function setCategoria($categoria)
    {
        $this->categoria = $categoria;
    }

    public static function createCard($farmaco)
    {
        echo '<div class="card">
                    <a href="detail.php?minsan=' . $farmaco->getMinsan() . '">
                        <div class="view overlay z-depth-2 rounded">
                            <img class="img-fluid w-100" src="' . $farmaco->getImg() . '" alt="' . $farmaco->getNomeProdotto() . '">
                        </div>
                    </a>

                    <div class="text-center pt-4">
                        <h6 class="mb-3">
                            <span class="text-danger mr-1">&euro;' . $farmaco->getPrezzo() / 100 . '</span>
                            <span class="text-grey"><s>&euro;' . $farmaco->getprezzoVecchio() / 100 . '</s></span>
                        </h6>
                        <h5>' . $farmaco->getNomeProdotto() . '</h5>
                        <p class="small text-muted text-uppercase mb-2">' . $farmaco->getCategoria() . '</p>
                        <form action="home.php" method="POST">
                            <input type="text" hidden value="' . $farmaco->getMinsan() . '" name="minsan">
                            <input type="submit" class="btn btn-primary btn-sm mr-1 mb-2" value="+ CARRELLO" name="carrello" id="carrello">
                            <input type="submit" class="btn btn-danger btn-sm px-3 mb-2 fa" value="&#xf004;" name="like" id="like">
                        </form>
                    </div>
                </div>';
    }
}
