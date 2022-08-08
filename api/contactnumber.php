<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization,X-Requested-With');

include_once '../config/database.php';
include_once '../models/contactNumber.php';
// Instantiate DB & connect
$database = new Database();
$db = $database->connect();
$contacNumberDb  = new ContactNumber($db);

switch ($_SERVER['REQUEST_METHOD']){
    case 'GET':

            $contacNumberDb->ContactId =  $_GET["contactid"];
            $result = $contacNumberDb->GetByContactId();
            $num = $result->rowCount();
            if ($num > 0) {
                $arr = array();
                $arr['data'] = array();
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $item = array(
                        'Id' => $Id,
                        'ContactId' => $ContactId,
                        'Name' => $Name,
                        'Value' => $Value
                    );
                    $arr['data'][] = $item;
                }
                echo json_encode($arr);

            } else {
                echo json_encode(
                    array('message' => 'No data encontrada')
                );
            }

        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);

        $contacNumberDb->ContactId = $data["ContactId"];
        $contacNumberDb->Name = $data["Name"];
        $contacNumberDb->Value = $data["Value"];

        if(is_null($data['Name']))
        {
            echo json_encode(
                array('message' => 'Debe de tener por lo menos un nombre')
            );
        }else {
            if ($contacNumberDb->Insert()) {

                echo json_encode(
                    array('message' => 'Numero  de  Contacto Creado')
                );
            } else {
                echo json_encode(
                    array('message' => 'Numero de  Contacto no creado')
                );
            }
        }

        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        $contacNumberDb->Id = $data["Id"];
        $contacNumberDb->ContactId = $data["ContactId"];
        $contacNumberDb->Name = $data["Name"];
        $contacNumberDb->Value = $data["Value"];

        if(is_null($data['Name']))
        {
            echo json_encode(
                array('message' => 'Debe de tener por lo menos un nombre')
            );
        }else {
            if ($contacNumberDb->Update()) {
                echo json_encode(
                    array('message' => 'Contacto actualizado')
                );
            } else {
                echo json_encode(
                    array('message' => 'Contacto no actualizado')
                );
            }
        }
        break;
    case 'DELETE':
        if(!is_null($_GET['id'])  ) {
            $contacNumberDb->Id = $_GET["id"];
            $contacNumberDb->Delete();

        }
        break;

}
?>