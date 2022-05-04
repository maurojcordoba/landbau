<?php

/**
 * Agricultura: captar los precios de pizarra diarios que aparecen en la Bolsa cereales de rosario
 * https://cac.bcr.com.ar/es/precios-de-pizarra
 */

require 'lib/simplehtmldom/simple_html_dom.php';

function bitacora($msg)
{
    #file_put_contents('m.txt',print_r($msg,true) . "\r\n", FILE_APPEND);
    echo sprintf("<pre>%s</pre>", print_r($msg, true) . "\r\n");
}

class Agricultura
{
    public $description = '';
    public $valor = '';

    function __construct($descripcion, $valor)
    {
        $this->description = trim(htmlentities($descripcion, ENT_QUOTES, 'UTF-8'));
        $this->valor = trim($valor);
    }
}

$url = 'https://cac.bcr.com.ar/es/precios-de-pizarra';

$html = file_get_html($url);

### Agricultura
$listaAgricultura = [];
//$html->dump(true);
foreach ($html->find('div[class=board-wrapper]') as $div) {
    $desc = $div->children(0)->innertext();
    $desc = preg_replace('/<span.*\/span>/', '', $desc);

    $valor = $div->children(1)->innertext();


    $obj = new Agricultura($desc, $valor);
    $listaAgricultura[] = $obj;
}
echo "<pre>";
print_r(json_encode($listaAgricultura));
echo "</pre>";


unset($html);
