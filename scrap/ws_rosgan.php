<?php

define("PROJECT_ROOT_PATH", __DIR__ . "/../");

require_once PROJECT_ROOT_PATH . "/inc/utils.php";
require_once PROJECT_ROOT_PATH . "/inc/config.php";
require_once PROJECT_ROOT_PATH . "/inc/Database.php";

/**
 * Ganadería:
 * Capta cada una de esas cotizaciones que aparecen en rosgan: vaquillonas, invernada.. etc.
 * https://www.rosgan.com.ar/
 */
require 'lib/simplehtmldom/simple_html_dom.php';


$url = 'https://www.rosgan.com.ar/';

$html = file_get_html($url);

### Ganaderia
# busco clase css: caja-indice
$listaGanaderia = [];
foreach ($html->find('li[class=caja-indice]') as $li) {
    $desc =  $li->childNodes(0)->plaintext;
    $valor = $li->childNodes(2)->plaintext;

    $listaGanaderia[] = [
        'descripcion' => $desc,
        'valor' => $valor
    ];
}


unset($html);

$db = new Database();

$json_data = json_encode($listaGanaderia, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
$web = 'rosgan';

$db->insertData('INSERT INTO ws_data (web,json_data) VALUES (?,?)', $web, $json_data);
