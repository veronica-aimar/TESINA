<?php

include('../simple_html_dom.php');
include('../DB/ManagerFarmaco.php');
include('../DB/Farmaco.php');
ini_set('max_execution_time', 300);

$pagina = 1;
while($amafarma = file_get_html('https://www.amafarma.com/farmaci-da-banco.html?p=' . $pagina)) {
    $listaProdotti = $amafarma->find('li[class="item product product-item"]');
    foreach($listaProdotti as $farmaco) {
        $minsan = $farmaco->find('[data-product-sku]', 0)->attr['data-product-sku'];
        $prezzoNuovo = $farmaco->find('span[class="price"]', 0)->plaintext;
        
        $farmacoConfronto = ManagerFarmaco::readById($minsan);
        $linkSito = $farmaco->find('div[class="product-item-info"] a', 0)->href;
        $dettagliSito = file_get_html($linkSito);

        $nomeProdotto = $farmaco->find('strong[class="product name product-item-name"] a', 0)->plaintext;
        $img = $farmaco->find('span[class="product-image-wrapper"] img', 1)->src;

        $percorsoDescrizione = 'div[class="product attribute description"] div[class="value"]';
        if($dettagliSito->find($percorsoDescrizione, 0)) {
            $descrizione = $dettagliSito->find($percorsoDescrizione, 0)->plaintext;
        }
        $categoria = $dettagliSito->find('span[class="categoria_riferimento"] span a', 0)->plaintext;

        if($farmaco->find('span[class="price"]', 1)) {
            $prezzoVecchio = $farmaco->find('span[class="price"]', 1)->plaintext;
        }

        $prezzoNuovo = str_replace(",", "", $prezzoNuovo);
        $prezzoVecchio = str_replace(",", "", $prezzoVecchio);
        $prezzoNuovo = str_replace("€", "", $prezzoNuovo);
        $prezzoVecchio = str_replace("€", "", $prezzoVecchio);

        $prezzoNuovo = intval($prezzoNuovo);
        $prezzoVecchio = intval($prezzoVecchio);   
    }
    $pagina++;
}

/*
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
$farmacoNuovo = new Farmaco($minsan, $nomeProdotto, $prezzoNuovo, $prezzoVecchio, $descrizione, $img, $categoria);
ManagerFarmaco::create($farmacoNuovo);
*/

?>