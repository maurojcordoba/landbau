<?php

/**
 * Agricultura: captar los precios de pizarra diarios que aparecen en la Bolsa cereales de rosario
 * https://cac.bcr.com.ar/es/precios-de-pizarra
 */

require_once '../inc/bootstrap.php';
require_once 'lib/simplehtmldom/simple_html_dom.php';

$url = 'https://cac.bcr.com.ar/es/precios-de-pizarra';

$html = file_get_html($url);

### Agricultura
$created = date(FORMAT_DATE_TIME);
$lista = ['created'=>$created, 'data'=>''];

foreach ($html->find('div[class=board-wrapper]') as $div) {
    $desc = $div->children(0)->plaintext;    
    $valor = $div->children(1)->plaintext;
    
    $productos[] = [
        'descripcion' => trim($desc),
        'valor' => trim($valor)
    ];
}

$lista['data'] = $productos;

$db = new Database();    
$json_data = json_encode($lista, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);    
$db->insert('cac', $json_data);    

unset($html);
