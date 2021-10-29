<?php

/**
 * Simple request response script
 *
 * Point you cURL request to this script to see all incoming data
 */

echo '*API*'. PHP_EOL;

echo 'Request Time: ' . time() . PHP_EOL;

echo 'Request Method: ' . print_r($_SERVER['REQUEST_METHOD'], true) . PHP_EOL;

if(FALSE === empty($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'])) {
    echo 'Request Header Method: ' . print_r($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'], true) . PHP_EOL;
}

echo 'Server Data: ' . print_r($_SERVER, true) . PHP_EOL;

echo 'Request Files: ' . print_r($_FILES, true) . PHP_EOL;

echo 'Request Data: ' . PHP_EOL;
// …Will only work with GET query parameters & POST form parameters
echo 'Request params: ' . print_r($_REQUEST, true) . PHP_EOL;
// …Other methods like DELETE & PUT, and request body content types like JSON
// are not converted into PHP superglobals automatically! Read the input stream instead.
// Note: The input stream may be accessed once only!
parse_str(file_get_contents('php://input'), $_INPUT);
echo 'Input Stream: ' . print_r($_INPUT, true) . PHP_EOL;