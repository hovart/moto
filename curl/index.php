<?php

$ch = curl_init("http://php.net");

curl_setopt($ch, CURLOPT_FILE, ch);
curl_setopt($ch, CURLOPT_HEADER, 0);

curl_exec($ch);
curl_close($ch);
fclose($ch);
?>