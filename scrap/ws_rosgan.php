<?php

/**
 * Ganadería:
 * Capta cada una de esas cotizaciones que aparecen en rosgan: vaquillonas, invernada.. etc.
 * https://www.rosgan.com.ar/
 */
require 'lib/simplehtmldom/simple_html_dom.php';

function bitacora($msg)
{
    #file_put_contents('m.txt',print_r($msg,true) . "\r\n", FILE_APPEND);
    echo sprintf("<pre>%s</pre>", print_r($msg, true) . "\r\n");
}

$url = 'https://www.rosgan.com.ar/';

$html = file_get_html($url);

### Ganaderia
# busco clase css: caja-indice
$listaGanaderia = [];
foreach ($html->find('li[class=caja-indice]') as $li) {
    $desc =  $li->childNodes(0)->plaintext;
    $valor = $li->childNodes(2)->plaintext;

    $listaGanaderia[] = [
        'descripcion' => htmlentities($desc),
        'valor' => $valor
    ];
}

echo "<pre>";
print_r(json_encode($listaGanaderia,JSON_UNESCAPED_SLASHES));
echo "</pre>";


unset($html);
