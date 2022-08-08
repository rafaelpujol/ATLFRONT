<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization,X-Requested-With');

include_once '../config/database.php';
include_once '../models/contact.php';
include_once '../models/contactNumber.php';
// Instantiate DB & connect
$database = new Database();
$db = $database->connect();
$contactDb = new Contact($db);
$contacNumberDb  = new ContactNumber($db);

   switch ($_SERVER['REQUEST_METHOD']){
       case 'GET':
            if(!is_null($_GET['id'])) {
                $contactDb->Id = $_GET['id'];
                $contactDb->GetById();
                $result = array(
                    'Id' => $contactDb->Id,
                    'Name' => $contactDb->Name,
                    'LastName' => $contactDb->LastName,
                    'Email' => $contactDb->Email,
                );

                echo json_encode(
                    array('data' => $result)
                );
            }else {

                $result = $contactDb->GetAll();
                $num = $result->rowCount();
                if ($num > 0) {
                    $arr = array();
                    $arr['data'] = array();
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $item = array(
                            'Id' => $Id,
                            'Name' => $Name,
                            'LastName' => $LastName,
                            'Email' => $Email,
                        );
                        $arr['data'][] = $item;
                    }
                    echo json_encode($arr);

                } else {
                    echo json_encode(
                        array('message' => 'No data encontrada')
                    );
                }
            }

        break;

       case 'POST':
           $data = json_decode(file_get_contents('php://input'), true);
           $contactDb->Id =  $data['Id'];
           $contactDb->Name = $data['Name'];
           $contactDb->LastName = $data['LastName'];
           $contactDb->Email =$data['Email'];

           if(is_null($data['Name']))
           {
               echo json_encode(
                   array('message' => 'Debe de tener por lo menos un nombre')
               );
           }else {
               if ($contactDb->Insert()) {
                   foreach ($data['Numbers'] as $d) {
                       $contacNumberDb->ContactId = $d["ContactId"];
                       $contacNumberDb->Name = $d["Name"];
                       $contacNumberDb->Value = $d["Value"];
                       $contacNumberDb->Insert();
                   }

                   echo json_encode(
                       array('message' => 'Contacto Creado')
                   );
               } else {
                   echo json_encode(
                       array('message' => 'Contacto no creado')
                   );
               }
           }

           break;

       case 'PUT':
           $data = json_decode(file_get_contents('php://input'), true);
           $contactDb->Id =  $data['Id'];
           $contactDb->Name = $data['Name'];
           $contactDb->LastName = $data['LastName'];
           $contactDb->Email =$data['Email'];

           if(is_null($data['Name']))
           {
               echo json_encode(
                   array('message' => 'Debe de tener por lo menos un nombre')
               );
           }else {
               if ($contactDb->Update()) {
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
           if(!is_null($_GET['id'])) {

               $contacNumberDb->ContactId = $_GET['id'];
               $contactDb->Id =  $_GET['id'];
               $contacNumberDb->DeleteByContactId();
               $contactDb->Delete();

           }
           break;

   }
?>