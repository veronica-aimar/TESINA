<?php

include('../simple_html_dom.php');
include('../DB/Farmaco.php');
include('../DB/ManagerFarmaco.php');

$arrayFarmaci = [];
$contatore=1;
while($easyFarma = file_get_html('https://www.topfarmacia.it/3366-farmaci-da-banco?page=' . $contatore)) {
    $listaFarmaci = $easyFarma->find('section[id="products"] div div[id="js-product-list"] div[class="products row products-grid"] div article[class="product-miniature product-miniature-default product-miniature-grid product-miniature-layout-1 js-product-miniature"]');

    foreach($listaFarmaci as $farmaco) {
        $minsan = $farmaco->find('div[class="product-reference text-muted"] a', 0)->plaintext;
        $prezzoNuovo = $farmaco->find('span[class="product-price"]', 0)->plaintext;
        
        $nomeProdotto = $farmaco->find('h3[class="h3 product-title"] a', 0)->plaintext;
        if($farmaco->find('span[class="regular-price text-muted"]', 0)) {
            $prezzoVecchio = $farmaco->find('span[class="regular-price text-muted"]', 0)->plaintext;
        }
        $img = $farmaco->find('div[class="thumbnail-container"] a img', 0)->attr['data-src'];
        $categoria = $farmaco->find('div[class="product-category-name text-muted"]', 0)->plaintext;

        $linkDettagli = $farmaco->find('div[class="thumbnail-container"] a', 0)->href;
        $dettagliArticolo = file_get_html($linkDettagli);
        $descrizione = $dettagliArticolo->find('div[class="rte-content"]', 0)->plaintext;

        // FORMATTAZIONE PREZZO
        $prezzoNuovo = str_replace(",", "", $prezzoNuovo);
        $prezzoVecchio = str_replace(",", "", $prezzoVecchio);
        $prezzoNuovo = str_replace("€", "", $prezzoNuovo);
        $prezzoVecchio = str_replace("€", "", $prezzoVecchio);

        $prezzoNuovo = intval($prezzoNuovo);
        $prezzoVecchio = intval($prezzoVecchio);
        // FORMATTAZIONE PREZZO

        $nuovoFarmaco = new Farmaco($minsan, $nomeProdotto, $prezzoNuovo, $prezzoVecchio, $descrizione, $img, $categoria);
        array_push($arrayFarmaci, $nuovoFarmaco);
    }
    $contatore++;
}

/* CASO
        NUOVO -> 1
        MIGLIORE -> 0
        PEGGIORE -> -1 
$caso = -1;
$farmacoConfronto = ManagerFarmaco::readById($minsan);
if(is_bool($farmacoConfronto)) {
    $caso = 1;
} else if($farmacoConfronto->getPrezzo() > $prezzoNuovo) {
    $caso = 0;
}

if($caso == 1) {
    ManagerFarmaco::create($nuovoFarmaco);
} else {
    ManagerFarmaco::update($nuovoFarmaco);
}
*/