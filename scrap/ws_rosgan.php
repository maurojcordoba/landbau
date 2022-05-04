<?php 
/**
 * Ganadería:
 * Capta cada una de esas cotizaciones que aparecen en rosgan: vaquillonas, invernada.. etc.
 * https://www.rosgan.com.ar/
 */
require 'lib/simplehtmldom/simple_html_dom.php';

function bitacora($msg){
    #file_put_contents('m.txt',print_r($msg,true) . "\r\n", FILE_APPEND);
    echo sprintf("<pre>%s</pre>", print_r($msg,true) . "\r\n");
}

class Ganaderia{
    public $description ='';
    public $valor='';

    function __construct($descripcion,$valor){
        $this->description = htmlentities($descripcion,ENT_QUOTES,'UTF-8');
        $this->valor = $valor;
    }    

}

$url = 'https://www.rosgan.com.ar/';

$html = file_get_html($url);

### Ganaderia
# busco clase css: caja-indice
$listaGanaderia = [];
foreach($html->find('li[class=caja-indice]') as $li) {        
    $desc =  $li->childNodes(0)->plaintext;    
    $valor = $li->childNodes(2)->plaintext;    

    $obj = new Ganaderia($desc,$valor);    
    $listaGanaderia[] = $obj;    
}

echo "<pre>";
print_r(json_encode($listaGanaderia));
echo "</pre>";

    
unset($html);
