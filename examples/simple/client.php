<?php

require dirname(dirname(__DIR__)) . '/vendor/autoload.php';

use Datto\JsonRpc\Http\Client;
use Datto\JsonRpc\Http\HttpException;
use Datto\JsonRpc\Response;

// Construct a client that can query your remote server over HTTP(S):
$client = new Client('http://localhost:8080/');

// Add the numbers "1" and "2":
$client->query(1, 'add', array(1, 2));

try {
    // Receive an array of server responses
    $responses = $client->send();

    printResponses($responses);
} catch (HttpException $exception) {
    $httpResponse = $exception->getHttpResponse();

    echo "HttpException\n";

    if (isset($httpResponse)) {
        echo " * statusCode: ", $httpResponse->getStatusCode(), "\n";
        echo " * reason: ", $httpResponse->getReason(), "\n";
        echo " * headers: ", json_encode($httpResponse->getHeaders()), "\n";
        echo " * version: ", $httpResponse->getVersion(), "\n";
    }
} catch (ErrorException $exception) {
    $message = $exception->getMessage();

    echo "ErrorException\n";
    echo " * message: {$message}\n";
    echo "\n";
    echo "See \"README.md\" to set up this example\n";
}

/**
 * @param Response[] $responses
 */
function printResponses(array $responses)
{
    foreach ($responses as $response) {
        $id = $response->getId();
        $isError = $response->isError();

        if ($isError) {
            $error = $response->getError();
            $errorProperties = array(
                'code' => $error->getCode(),
                'message' => $error->getMessage(),
                'data' => $error->getData()
            );

            echo "id: {$id}, error: ", json_encode($errorProperties), "\n";
        } else {
            $result = $response->getResult();

            echo "id: {$id}, result: ", json_encode($result), "\n";
        }
    }
}
