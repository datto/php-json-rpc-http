<?php

namespace Datto;

require __DIR__ . '/vendor/autoload.php';

use Example\AuthenticatedClient;
use Datto\JsonRpc\Http\HttpException;
use Datto\JsonRpc\Http\HttpResponse;
use Datto\JsonRpc\Response;
use ErrorException;

$url = 'http://localhost:8080/serverAuthenticated.php';

// Provide an invalid username and password:
$username = 'invalid';
$password = 'invalid';

// Construct a client that can query your remote server over HTTP(S):
$client = new AuthenticatedClient($url, $username, $password);

// Add the numbers "1" and "2":
$client->query(1, 'add', array(1, 2));

// Send your requests to the remote server:
try {
    $responses = $client->send();
    onSuccess($responses);
} catch (HttpException $exception) {
    $httpResponse = $exception->getHttpResponse();
    onHttpError($httpResponse);
} catch (ErrorException $exception) {
    onException($exception);
}

function onSuccess(array $responses)
{
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
}

function onHttpError(HttpResponse $error)
{
    echo "HttpException\n",
        " * statusCode: ", $error->getStatusCode(), "\n",
        " * reason: ", $error->getReason(), "\n",
        " * headers: ", json_encode($error->getHeaders()), "\n",
        " * version: ", $error->getVersion(), "\n";
}

function onException(ErrorException $exception)
{
    $message = $exception->getMessage();

    echo "See the \"README.md\" to set up this example!\n";
}
