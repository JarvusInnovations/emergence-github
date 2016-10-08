<?php

namespace Emergence\GitHub;

use Exception;
use Emergence\EventBus;


class Connector extends \Emergence\Connectors\AbstractConnector
{
    public static $webhookSecret;

    public static function handleRequest($action = null)
    {
        switch ($action ?: $action = static::shiftPath()) {
            case 'webhooks':
                return static::handleWebhooksRequest();
            case 'oauth':
                return static::handleOAuthRequest();
            default:
                return parent::handleRequest($action);
        }
    }

    public static function handleWebhooksRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            throw new Exception('request method must be POST');
        }

        if ($_SERVER['HTTP_CONTENT_TYPE'] != 'application/json') {
            throw new Exception('request content-type must be application/json');
        }

        if (!$rawData = file_get_contents('php://input')) {
            throw new Exception('request body missing');
        }

        if (static::$webhookSecret) {
            if (!extension_loaded('hash')) {
                throw new Exception('hash extension not available');
            }

            if (empty($_SERVER['HTTP_X_HUB_SIGNATURE'])) {
                throw new Exception('signature header missing');
            }

            list($algo, $hash) = explode('=', $_SERVER['HTTP_X_HUB_SIGNATURE'], 2) + ['', ''];

            if (!in_array($algo, hash_algos())) {
                throw new Exception('unsupported hash algorithm: ' . $algo);
            }

            if ($hash != hash_hmac($algo, $rawData, static::$webhookSecret)) {
                throw new Exception('invalid signature');
            }
        }

        if (!$data = json_decode($rawData, true)) {
            throw new Exception('failed to parse request body');
        }

        EventBus::fireEvent($_SERVER['HTTP_X_GITHUB_EVENT'], __NAMESPACE__, $data);

        return static::respond('webhookReceived', ['success' => true], 'json');
    }

    public static function handleOAuthRequest()
    {
        if (empty($_GET['code'])) {
            throw new Exception('code missing');
        }

        $responseData = API::request('https://github.com/login/oauth/access_token', [
            'skipAuth' => true,
            'headers' => [
                'Accept: application/json'
            ],
            'post' => [
                'client_id' => API::$clientId,
                'client_secret' => API::$clientSecret,
                'code' => $_GET['code']
            ]
        ]);

        if (empty($responseData['access_token'])) {
            \Debug::dumpVar($responseData);
            throw new Exception('access_token not returned by GitHub');
        }

        return static::respond('message', [
            'message' => 'Access token issued by GitHub: ' . $responseData['access_token']
        ]);
    }
}