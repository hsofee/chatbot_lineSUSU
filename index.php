// start 21/11/2564
<?php 
    /*Get Data From POST Http Request*/
    $datas = file_get_contents('php://input');
    /*Decode Json From LINE Data Body*/
    $deCode = json_decode($datas,true);

    file_put_contents('log.txt', file_get_contents('php://input') . PHP_EOL, FILE_APPEND);

    $replyToken = $deCode['events'][0]['replyToken'];
    $userId = $deCode['events'][0]['source']['userId'];
    $text = $deCode['events'][0]['message']['text'];

    $messages = [];
    $messages['replyToken'] = $replyToken;
    $messages['messages'][0] = getFormatTextMessage("หวัดดีครับ");

    $encodeJson = json_encode($messages);

    $LINEDatas['url'] = "https://api.line.me/v2/bot/message/reply";
    $LINEDatas['token'] = "0AUPsNcsGyj4cXXxV/R/Oj+PRQx6m1UIuQ/Cd8+BFfsqn+WoBeUIhSvWtK1fos4n1I+AUFesQPB4mZk/beV2tv8+8zQgZ0cT7MnVUvrDC4bJVEA+Hb5E+80CdKzclPpIIaWDPxEXaP7DEOlq/i7TjAdB04t89/1O/w1cDnyilFU=";

      //  access scr :
      //b3a4534460a5b18f1f12c92af7c32935
      //  channel id : 1656650668
      //  command 1
      // ./ngrok authtoken 21Ar6CKbsW14GTr2pEulWQU50Sa_88XxaorY6C2WQkLwKnrtddir
      //  https://85d5-2405-9800-b560-956e-658f-ad5f-ce67-16f2.ngrok.io -> http://localhost:3000
      // 2. path c:\xampp\php
      // 2.  php -S 127.0.0.1:8080
//3. .ngrok http 80
      $results = sentMessage($encodeJson,$LINEDatas);

    /*Return HTTP Request 200*/
    http_response_code(200);

    function getFormatTextMessage($text)
    {
        $datas = [];
        $datas['type'] = 'text';
        $datas['text'] = $text;

        return $datas;
    }

    function sentMessage($encodeJson,$datas)
    {
        $datasReturn = [];
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $datas['url'],
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $encodeJson,
          CURLOPT_HTTPHEADER => array(
            "authorization: Bearer ".$datas['token'],
            "cache-control: no-cache",
            "content-type: application/json; charset=UTF-8",
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $datasReturn['result'] = 'E';
            $datasReturn['message'] = $err;
        } else {
            if($response == "{}"){
            $datasReturn['result'] = 'S';
            $datasReturn['message'] = 'Success';
            }else{
            $datasReturn['result'] = 'E';
            $datasReturn['message'] = $response;
            }
        }

        return $datasReturn;
    }
?>
