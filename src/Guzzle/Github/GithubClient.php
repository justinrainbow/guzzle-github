<?php

namespace Guzzle\Github;

use Guzzle\Service\Client;
use Guzzle\Service\Inspector;
use Guzzle\Service\Description\XmlDescriptionBuilder;
use Guzzle\Http\Message\RequestInterface;

class GithubClient extends Client
{
    /**
     * @var string Client ID
     */
    protected $clientId;

    /**
     * @var string Client secret
     */
    protected $clientSecret;

    /**
     * @var string Access Token
     */
    protected $accessToken;

    /**
     * @var string Redirect URI
     */
    protected $redirectUri;

    /**
     * Factory method to create a new GithubClient
     *
     * @param array|Collection $config Configuration data. Array keys:
     *    base_url - Base URL of web service
     *
     * @return GithubClient
     *
     * @TODO update factory method and docblock for parameters
     */
    public static function factory($config)
    {
        $default = array(
            'base_url' => 'https://api.github.com',
            'scheme'   => 'https'
        );
        $required = array('base_url', 'client_id', 'client_secret');
        $config = Inspector::prepareConfig($config, $default, $required);

        $client = new self(
            $config->get('base_url'),
            $config->get('client_id'),
            $config->get('client_secret')
        );
        $client->setConfig($config);

        // Uncomment the following two lines to use an XML service description
        $client->setDescription(XmlDescriptionBuilder::build(__DIR__ . '/client.xml'));

        return $client;
    }

    /**
     * Client constructor
     *
     * @param string $baseUrl Base URL of the web service
     * @param string $clientId
     * @param string $clientSecret
     */
    public function __construct($baseUrl, $clientId, $clientSecret)
    {
        parent::__construct($baseUrl);
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    public function createRequest($method = RequestInterface::GET, $uri = null, $headers = null, $body = null)
    {
        $request = parent::createRequest($method, $uri, $headers, $body);

        if (isset($this->accessToken)) {
            $request->setHeader('Authorization', 'bearer ' . $this->accessToken);
        }

        return $request;
    }

    public function setAccessToken($token)
    {
        $this->accessToken = $token;
    }

    public function setRedirectUri($redirectUri)
    {
        $this->redirectUri = $redirectUri;
    }

    public function getDefaultHeaders()
    {
        $headers = parent::getDefaultHeaders();

        if (isset($this->accessToken)) {
            $headers->add('Authorization', 'bearer ' . $this->accessToken);
        }

        return $headers;
    }
}