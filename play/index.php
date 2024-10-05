<?php
// To work with https, enable the php_openssl in the php.ini file, i.e.,
// extension=php_openssl.dll
$url = 'https://www.cs.utep.edu/cheon/cs3360/project/omok/play/';
$pid = '6029ae684ab97';
$xy = '7,8';
$response = @file_get_contents("$url?pid=$pid&move=$xy");
if ($response) {
    echo $response;
}
?>
