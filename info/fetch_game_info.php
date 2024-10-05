<?php
// To work with https, enable the php_openssl in the php.ini file, i.e.,
// extension=php_openssl.dll
$url = 'https://www.cs.utep.edu/cheon/cs3360/project/omok/info/';
$response = @file_get_contents($url);
if ($response) {
    echo $response;
}
?>
