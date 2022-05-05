<?php

require_once '../inc/bootstrap.php';

header_remove('Set-Cookie');
//CORS
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods: GET');
header('Content-Type: application/json');


$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

if (isset($uri[3]) || (!empty($uri[3]))) {
    
    $db = new Database();
    $result = $db->getData($uri[3]);
    
    if (!empty($result)){    
        echo $result;
    }else{
        echo json_encode(['msg' => 'No se encontraron registros']);
        
    }
    
    exit();
}
