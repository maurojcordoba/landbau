<?php


#### Modificar para correr en el mismo dia
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'http://www.mercadodeliniers.com.ar/dll/hacienda2.dll/haciinfo000013',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => 'utf-8',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => 'ID=&CP=&FLASH=&USUARIO=SIN%20IDENTIFICAR&OPCIONMENU=&OPCIONSUBMENU=&txtFechaIni=03%2F05%2F2022&txtFechaFin=03%2F05%2F2022&Aceptar=Ver%20consulta',
    CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded'),
));

$response = curl_exec($curl);

curl_close($curl);
#### Modificar para correr en el mismo dia

/**
 * Índice arrendamiento: captar los precios de pizarra diarios que aparecen en 
 * http://www.mercadodeliniers.com.ar/dll/hacienda2.dll/haciinfo000013
 */

require 'lib/simplehtmldom/simple_html_dom.php';

function bitacora($msg)
{
    #file_put_contents('m.txt',print_r($msg,true) . "\r\n", FILE_APPEND);
    echo sprintf("<pre>%s</pre>", print_r($msg, true) . "\r\n");
}

$url = 'http://www.mercadodeliniers.com.ar/dll/hacienda2.dll/haciinfo000013';

//$html = file_get_html($url);
$html = new simple_html_dom(utf8_encode($response));

$tables = $html->find('table');
$table = $tables[4];

$trs = $table->find('tr');
$tr = $trs[12];

$listaArrendamiento = [];

$tds = $tr->find('td');
$listaArrendamiento[] = [
    'fecha' => $tds[0]->innertext(),
    'cab_ingresadas' => $tds[1]->innertext(),
    'importe' => $tds[2]->innertext(),
    'indice_arrendamiento' => $tds[3]->innertext()
];


echo "<pre>";
print_r(json_encode($listaArrendamiento,JSON_UNESCAPED_SLASHES));
echo "</pre>";
