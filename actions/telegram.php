<?php

/**
 * Send to telegram action
 */

if(!function_exists('action_telegram')){
    function action_telegram($data){
        $token = @$data['token'];
        $chat_id = @$data['chat_id'];
        $message = @$data['message'];

        $url = "https://api.telegram.org/bot" . $token . "/sendMessage?chat_id=" . $chat_id;
        $url = $url . "&text=" . urlencode($message);
        $ch = curl_init();
        $optArray = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true
        );
        curl_setopt_array($ch, $optArray);
        $response = curl_exec($ch);
        curl_close($ch);

        return array(
            'success' => true,
            'response' => $response
        );
    }
}