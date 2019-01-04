<?php

namespace Example;

use Datto\JsonRpc\Http;

/**
 * This is an example of an authenticated API:
 * @see https://en.wikipedia.org/wiki/Basic_access_authentication Basic access authentication
 *
 * Of course, you can use any type of authentication that you prefer!
 */
class AuthenticatedClient extends Http\Client
{
    /**
     * Client constructor.
     * @param string $uri
     * @param string $username
     * @param string $password
     */
    public function __construct($uri, $username, $password)
    {
        $authentication = base64_encode("{$username}:{$password}");

        $headers = array(
            'Authorization' => "Basic {$authentication}"
        );

        parent::__construct($uri, $headers);
    }
}
