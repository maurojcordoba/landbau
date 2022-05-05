<?php

/**
 * Avicultura: precio del huevo SEMANAL
 * https://capia.com.ar/estadisticas/precio-del-huevo-semanal
 */
require_once '../inc/bootstrap.php';
require_once 'lib/simplehtmldom/simple_html_dom.php';


$url = 'https://capia.com.ar/estadisticas/precio-del-huevo-semanal';

$html = file_get_html($url);

### Avicultura
$listaAvicultura = [];

$div = $html->find('div[class=items-leading clearfix]', 0);

//Semana
$semana = trim($div->find('div[class=entry-header] a', 0)->plaintext);

$created = date(FORMAT_DATE_TIME);
$listaAvicultura = ['created' => $created, 'data' => ['semana' => $semana, 'categorias' => '']];

$trs = $div->find('table tr');



//PRECIO DE VENTA DE PRODUCTOS AVÍCOLAS
$desc = htmlentities($trs[1]->find('td strong')[1]->plaintext);

for ($i = 4; $i <= 6; $i++) {
    $detalle = trim($trs[$i]->childNodes(0)->plaintext);
    $unidad  = trim($trs[$i]->childNodes(1)->plaintext);
    $precio  = trim($trs[$i]->childNodes(2)->plaintext);

    $precio_cajon = '';

    //detecta &nbps;
    if (ord(trim($trs[$i]->childNodes(3)->plaintext)) != 194)
        $precio_cajon = trim($trs[$i]->childNodes(3)->plaintext);

    $productos[] = [
        'detalle' => $detalle,
        'unidad' => $unidad,
        'precio' => $precio,
        'precioxcajon' => $precio_cajon,
    ];
}
$categorias[] = [
    'descripcion' => $desc,
    'productos' => $productos
];



//PRECIO PAGADO POR INDUSTRIA
$desc =  trim($trs[8]->childNodes(0)->plaintext);

for ($i = 11; $i <= 13; $i++) {
    $detalle = trim($trs[$i]->childNodes(0)->plaintext);
    $unidad  = trim($trs[$i]->childNodes(1)->plaintext);
    $precio  = trim($trs[$i]->childNodes(2)->plaintext);

    $productos[] = [
        'detalle' => $detalle,
        'unidad' => $unidad,
        'precio' => $precio
    ];
}

$categorias[] = [
    'descripcion' => $desc,
    'productos' => $productos
];



//PRECIOS DE INSUMOS AVÍCOLAS
$desc =  trim($trs[15]->childNodes(0)->plaintext);
for ($i = 18; $i <= 32; $i++) {
    $detalle = trim($trs[$i]->childNodes(0)->plaintext);
    $unidad  = trim($trs[$i]->childNodes(1)->plaintext);
    $precio  = trim($trs[$i]->childNodes(2)->plaintext);

    $productos[] = [
        'detalle' => $detalle,
        'unidad' => $unidad,
        'precio' => $precio
    ];
}
$categorias[] = [
    'descripcion' => $desc,
    'productos' => $productos
];

$listaAvicultura['data']['categorias'] = $categorias;

$db = new Database();
$json_data = html_entity_decode(json_encode($listaAvicultura, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
$db->insert('capia', $json_data);

unset($html);
