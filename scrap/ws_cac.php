<?php

/**
 * Agricultura: captar los precios de pizarra diarios que aparecen en la Bolsa cereales de rosario
 * https://cac.bcr.com.ar/es/precios-de-pizarra
 */

require 'lib/simplehtmldom/simple_html_dom.php';


$url = 'https://cac.bcr.com.ar/es/precios-de-pizarra';

$html = file_get_html($url);

### Agricultura
$listaAgricultura = [];

foreach ($html->find('div[class=board-wrapper]') as $div) {
    $desc = $div->children(0)->plaintext;    
    $valor = $div->children(1)->plaintext;

    $listaAgricultura[] = [
        'descripcion' => trim(htmlentities($descripcion)),
        'valor' => trim($valor)
    ];
}
echo "<pre>";
print_r(json_encode($listaAgricultura,JSON_UNESCAPED_SLASHES));
echo "</pre>";


unset($html);
