<?php
    //Se incluyen archivos de configuracion y de conexion a base de datos
    include_once('config.php');
    include_once('dbconextion.php');
    //Se creean las constantes a utilizar
    const WEBHOOK_TOKEN = config::WEBHOOK_TOKEN;
    const WEBHOOK_URL = config::WEBHOK_URL;
    const METAAPI_URL = config::METAAPI_URL;
    const METAAPI_TOKEN = config::METAAPI_TOKEN;
    const CHATGPT_TOKEN = config::CHATGPT_TOKEN;

    //Se verifica el Token del lado del webhook
    function verifyToken($req,$res){
            try{
                $token = $req['hub_verify_token'];
                $challenge = $req['hub_challenge'];

                if (isset($challenge) && isset($token) && $token == WEBHOOK_TOKEN) {
                    $res->send($challenge);
                }else{
                    $res->status(400)->send();
                }
            }catch(Exception $e){
                $res ->status(400)->send();
            }
    }

    //Se recibe el mensaje con el objetico de generar una respuesta inmediata
    function reciveMessages($req,$res){
        //Abri conexion con base de datos
        $conn = new PDO_DB();
        try{
            //Lectura del mensaje recivido
            $entry = $req['entry'][0];
            $changes = $entry['changes'][0];
            $value = $changes['value'];
            $objetmessage = $value['messages'];
            $message = $objetmessage[0];
            $body = $message['text']['body'];
            $number = $message['from'];
            //Carga todos los settings

            $sql = "SELECT * FROM chatbot_settings";
            $rowsettings = $conn->fetchAll($sql);
            //file_put_contents("log_wh_error.txt", $rowsettings, FILE_APPEND);
            try{  
                if($rowsettings){
                    foreach ($rowsettings as $rowsetting) {
                        $setting_name = $rowsetting['setting_name'];
                        if ($setting_name === "default_chatgpt_text") {
                            $chatgpt = $rowsetting['setting_value'];
                        } elseif ($setting_name === "default_chatbot") {
                            $chatbotid = $rowsetting['setting_value'];
                        } elseif ($setting_name === "default_text") {
                            $chatbotdefaulttext = $rowsetting['setting_value'];
                        } elseif ($setting_name === "default_chatgpt") {
                            $chagptdefault = $rowsetting['setting_value'];
                        }
                    } 
                }else{
                    $chatgpt = "chatgpt";
                    $chatbotid = 1;
                    $chatbotdefaulttext = "Menu";
                }
            }catch(Exception $e){
                //Si tuvieramos un error, este se escribira en el archivo log_wh_error.txt
                file_put_contents("log_wh_error.txt", $e->getMessage(), FILE_APPEND);
                exit ;
            }
            //Verifica si el contenido del mensaje trae la clave de chatgpt
            if (strpos($body, $chatgpt)!== false) {
                $text_to_chatgpt = str_replace($chatgpt,"", $body);
                //Solicita respuesta a ChatGPT
                $chatgpt_answer = sendChatGPT($text_to_chatgpt);
                sendMessages($chatgpt_answer, "text", "", "", "", "", "", $number);
            } else {
                //Con el texto del mensaje recibido se procede a realizar la consulta a la base de datos
                $sql = "SELECT * FROM chatbot WHERE receive_msg = '$body' AND chatid = '$chatbotid'";
                $row = $conn->fetchRow($sql); 
                //Si existe el texto en la base de datos devuelve el regsitro para proceder a responder el mensaje
                if($row){
                    try{
                        $sendmessage = $row["send_msg"];
                        $msg_type = $row["msg_type"];
                        $msg_latitude = $row["msg_latitude"];
                        $msg_longitude = $row["msg_longitude"];
                        $msg_name = $row["msg_name"];
                        $msg_address = $row["msg_address"];
                        $msg_url = $row["msg_url"];
                        //Llama a la funcion para el envio del mensaje
                        sendMessages($sendmessage, $msg_type, $msg_latitude, $msg_longitude, $msg_name, $msg_address, $msg_url, $number);
                    }catch(Exception $e){
                        //Si tuvieramos un error, este se escribira en el archivo log_wh_error.txt
                        file_put_contents("log_wh_error.txt", $e->getMessage(), FILE_APPEND);
                        exit ;
                    }
                } else {
                    //Verifica si cuando se recibe un texto desconocido va a consultar chatgpt
                    if ($chagptdefault === "yes") {
                        //Solicita respuesta a ChatGPT
                        $chatgpt_answer = sendChatGPT($body);
                        sendMessages($chatgpt_answer, "text", "", "", "", "", "", $number);
                    } else {
                        //Al no encontrar coincidencias en la base de datos, devuelve el mensaje que la opcion no esta disponible
                        $sql = "SELECT * FROM chatbot WHERE receive_msg = '$chatbotdefaulttext' AND chatid = '$chatbotid'";
                        $rowdefault = $conn->fetchRow($sql); 
                        if($rowdefault){
                            $sendmessage = $rowdefault["send_msg"];
                            $msg_type = $rowdefault["msg_type"];
                            $msg_latitude = $rowdefault["msg_latitude"];
                            $msg_longitude = $rowdefault["msg_longitude"];
                            $msg_name = $rowdefault["msg_name"];
                            $msg_address = $rowdefault["msg_address"];
                            $msg_url = $rowdefault["msg_url"];
                            //Llama a la funcion para el envio del mensaje
                            sendMessages($sendmessage, $msg_type, $msg_latitude, $msg_longitude, $msg_name, $msg_address, $msg_url, $number);
                        }else{
                            $sendmessage = "Option not Available";
                            sendMessages($sendmessage, "text", "", "", "", "", "", $number);
                    }
                }
                }
            }
            //Guarda informacion de mensaje recibido en el archivo log.txt (opcional)
            $file_log = fopen("log.txt","a");
            $text_log = json_encode($value);
            fwrite($file_log,$text_log);
            fclose($file_log);
            $res->send("EVENT_RECEIVED");
        }catch(Exception $e){
            $res->send("EVENT_RECEIVED");
        }
    }

    function sendMessages($body, $msg_type, $msg_latitude, $msg_longitude, $msg_name, $msg_address, $msg_url, $number) {
        //Si el mensaje es de tipo Texto utiliza la siguiente rutina
        if ($msg_type === "text") {
            $data = json_encode([
                "messaging_product" => "whatsapp",
                "recipient_type" => "individual",
                "to" => $number,
                "type" => "text",
                "text" => [
                    "preview_url" => false,
                    "body" => $body
                ]
            ]);
        //Si el mensaje es de tipo Texto con un URL utiliza la siguiente rutina
        } elseif ($msg_type === "text_url") {
            $data = json_encode([
                "messaging_product" => "whatsapp",
                "recipient_type" => "individual",
                "to" => $number,
                "type" => "text",
                "text" => [
                    "preview_url" => true,
                    "body" => $body
                ]
            ]);
        //Si el mensaje es de tipo Imagen utiliza la siguiente rutina
        } elseif ($msg_type === "image") {
            $data = json_encode([
                "messaging_product" => "whatsapp",
                "recipient_type" => "individual",
                "to" => $number,
                "type" => "image",
                "image" => [
                    "link" => $body
                ]
             ]);
        //Si el mensaje es de tipo Audio utiliza la siguiente rutina
        } elseif ($msg_type === "audio") {
            $data = json_encode([
                "messaging_product" => "whatsapp",
                "recipient_type" => "individual",
                "to" => $number,
                "type" => "audio",
                "audio" => [
                    "link" => $msg_url
            ]
         ]);
        //Si el mensaje es de tipo Imagen utiliza la siguiente rutina
        } elseif ($msg_type === "video") {
            $data = json_encode([
                "messaging_product" => "whatsapp",
                "recipient_type" => "individual",
                "to" => $number,
                "type" => "video",
                "video" => [
                    "caption" => $body,
                    "link" => $msg_url
            ]
        ]);
        } elseif ($msg_type === "document") {
            $data = json_encode([
                "messaging_product" => "whatsapp",
                "recipient_type" => "individual",
                "to" => $number,
                "type" => "document",
                "document" => [
                    "link" => $msg_url,
                    "caption" => $body
            ]
        ]);
        //Si el mensaje es de tipo Location, utiliza la siguiente rutinna
        } elseif ($msg_type === "location") {
            $data = json_encode([
                "messaging_product" => "whatsapp",
                "recipient_type" => "individual",
                "to" => $number,
                "type" => "location",
                "location" => [
                    "latitude" => $msg_latitude,
                    "longitude" => $msg_longitude,
                    "name" => $msg_name,
                    "address" => $msg_address
                ]
            ]);
        //Si el tipo de mensaje no coincide con ninguna opcion, se procede a enviar el mensaje como tipo Texto.
        } else {
            $data = json_encode([
                "messaging_product" => "whatsapp",
                "recipient_type" => "individual",
                "to" => $number,
                "type" => "text",
                "text" => [
                    "preview_url" => false,
                    "body" => $body
                ]
            ]);
        }   
            //Rutina para envio de mensaje con sus cabeceras, asi META estara Feliz
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => METAAPI_URL,
            CURLOPT_HEADER => false,
            CURLOPT_AUTOREFERER => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer '.METAAPI_TOKEN
            ),
            ));

            $response = curl_exec($curl);
            $info = curl_getinfo($curl);
            $httpCode = (int) $info['http_code'];

            //Captura y manda a escribe en el archivo log_wh_error.txt cualquier mensaje de error
            if($response === false || !in_array($httpCode, [200,201,204])){
                $error = curl_error($curl);
                $date = date("Y-m-d H:i:s");
                throw new RuntimeException("[$date] Facebook API Error: {$info['http_code']} -> $response | using data: $data\n\n");
            }

            curl_close($curl);
            echo $response;
    }
    //Consulta a ChatGPT
    function sendChatGPT($text_to_chatgpt) {
        $data = [
            'model' => 'text-davinci-003',
            'prompt' => $text_to_chatgpt,
            'temperature' => 0.7,
            'max_tokens' => 300,
            'n' => 1,
            'stop' => ['\n']
        ];
        $ch = curl_init('https://api.openai.com/v1/completions');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . CHATGPT_TOKEN
        ));
        $response = curl_exec($ch);
        $responseArr = json_decode($response, true);
        return $responseArr['choices'][0]['text'];
    }

    if ($_SERVER['REQUEST_METHOD']==='POST'){
        $input = file_get_contents('php://input');
        $data = json_decode($input,true);

        reciveMessages($data,http_response_code());
    }else if($_SERVER['REQUEST_METHOD']==='GET'){
        if(isset($_GET['hub_mode']) && isset($_GET['hub_verify_token']) && isset($_GET['hub_challenge']) && $_GET['hub_mode'] === 'subscribe' && $_GET['hub_verify_token'] === TOKEN_RING2ALL){
            echo $_GET['hub_challenge'];
        }else{
            http_response_code(403);
        }
    }
?>