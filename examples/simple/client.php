<?php

require __DIR__ . '/../../vendor/autoload.php';

use Datto\JsonRpc\Http\Client;
use Datto\JsonRpc\Http\HttpException;
use Datto\JsonRpc\Response;

// Construct a client that can query your remote server over HTTP(S):
$client = new Client('http://localhost:8080/');

// Choose an arbitrary id for your request:
$id = 1;

// Add the numbers "1" and "2":
$client->query($id, 'add', array(1, 2));

// Add the strings "a" and "b" (this will generate an error):
$client->query(2, 'add', array('a', 'b'));

try {
    // Send your requests to the remote server:
    $responses = $client->send();
} catch (HttpException $exception) {
    $httpResponse = $exception->getHttpResponse();

    echo "HttpException\n",
        " * statusCode: ", $httpResponse->getStatusCode(), "\n",
        " * reason: ", $httpResponse->getReason(), "\n",
        " * headers: ", json_encode($httpResponse->getHeaders()), "\n",
        " * version: ", $httpResponse->getVersion(), "\n";

    exit(1);
} catch (ErrorException $exception) {
    $message = $exception->getMessage();

    echo "ErrorException\n",
        " * message: {$message}\n",
        "\n",
        "See the \"README.md\" to set up this example!\n";

    exit(1);
}

// View the results:
/**
 * @var Response[] $responses
 */
foreach ($responses as $response) {
    $id = $response->getId();

    echo "id: {$id}\n";

    if ($response->isError()) {
        $error = $response->getError();
        $code = $error->getCode();
        $message = $error->getMessage();
        $data = $error->getData();

        echo " * error (as expected):\n",
            "    * code: ", json_encode($code), "\n",
            "    * message: ", json_encode($message), "\n",
            "    * data (if any): ", json_encode($data), "\n";
    } else {
        $result = $response->getResult();

        echo " * result: ", json_encode($result), "\n";
    }

    echo "\n";
}
