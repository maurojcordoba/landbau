<?php
/**
 * Índice arrendamiento: captar los precios de pizarra diarios que aparecen en 
 * http://www.mercadodeliniers.com.ar/dll/hacienda2.dll/haciinfo000013
 */

require_once '../inc/bootstrap.php';

require_once 'lib/simplehtmldom/simple_html_dom.php';

$url = 'http://www.mercadodeliniers.com.ar/dll/hacienda2.dll/haciinfo000013';

#### Modificar para correr en dias anteriores
// $curl = curl_init();
// curl_setopt_array($curl, array(
//     CURLOPT_URL => $url,
//     CURLOPT_RETURNTRANSFER => true,
//     CURLOPT_ENCODING => 'utf-8',
//     CURLOPT_MAXREDIRS => 10,
//     CURLOPT_TIMEOUT => 0,
//     CURLOPT_FOLLOWLOCATION => true,
//     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//     CURLOPT_CUSTOMREQUEST => 'POST',
//     CURLOPT_POSTFIELDS => 'ID=&CP=&FLASH=&USUARIO=SIN%20IDENTIFICAR&OPCIONMENU=&OPCIONSUBMENU=&txtFechaIni=04%2F05%2F2022&txtFechaFin=04%2F05%2F2022&Aceptar=Ver%20consulta',
//     CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded'),
// ));
// $response = curl_exec($curl);
// curl_close($curl);

// $html = new simple_html_dom(utf8_encode($response));

#### Modificar para correr en dias anteriores

$html = file_get_html($url);

$tables = $html->find('table');
$table = $tables[4];

$trs = $table->find('tr');
$tr = $trs[12];


$created = date(FORMAT_DATE_TIME);
$lista = ['created'=>$created, 'data'=>''];

$tds = $tr->find('td');
// si hay datos
if (!empty($tds)){
    $productos[] = [
        'fecha' => normalize($tds[0]->plaintext),
        'cab_ingresadas' => $tds[1]->plaintext,
        'importe' => $tds[2]->plaintext,
        'indice_arrendamiento' => $tds[3]->plaintext
    ];

    $lista['data'] = $productos;

    $db = new Database();    
    $json_data = json_encode($lista, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);    
    $db->insert('mliniers', $json_data);    
}

unset($html);