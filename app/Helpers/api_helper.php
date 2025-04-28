<?php

if (!function_exists('callAPI')) {
    function callAPI($method, $url, $data = false, $token = '', $isMultipart = false)
    {
        $curl = curl_init();

        switch (strtoupper($method)) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data) curl_setopt($curl, CURLOPT_POSTFIELDS, $isMultipart ? $data : json_encode($data));
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($data) curl_setopt($curl, CURLOPT_POSTFIELDS, $isMultipart ? $data : json_encode($data));
                break;
            default:
                if ($data) $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $headers = [];

        if ($isMultipart) {
            $headers[] = "Authorization: Bearer $token";

        } else {
            $headers = [
                'Content-Type: application/json'
            ];
            if (!empty($token)) {
                $headers[] = "Authorization: Bearer $token";
            }
        }

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($curl);

        if (curl_errno($curl)) {
            curl_close($curl);
            return [];
        }

        curl_close($curl);

        return json_decode($result, true);
    }
}
