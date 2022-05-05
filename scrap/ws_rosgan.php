<?php

/**
 * Ganadería:
 * Capta cada una de esas cotizaciones que aparecen en rosgan: vaquillonas, invernada.. etc.
 * https://www.rosgan.com.ar/
 */

require_once '../inc/bootstrap.php';

require_once 'lib/simplehtmldom/simple_html_dom.php';


$url = 'https://www.rosgan.com.ar/';

$html = file_get_html($url);

### Ganaderia
# busco clase css: caja-indice
$created = date(FORMAT_DATE_TIME);
$listaGanaderia = ['created'=>$created, 'data'=>''];

foreach ($html->find('li[class=caja-indice]') as $li) {
    $desc =  $li->childNodes(0)->plaintext;
    $valor = $li->childNodes(2)->plaintext;

    $productos[] = [
        'descripcion' => $desc,
        'valor' => $valor
    ];
}
$listaGanaderia['data'] = $productos;

unset($html);

$db = new Database();
$json_data = json_encode($listaGanaderia, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
$db->insert('rosgan', $json_data);
