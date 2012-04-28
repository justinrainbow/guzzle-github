<?php

namespace Guzzle\Github;

use Guzzle\Service\Client;
use Guzzle\Service\Inspector;
use Guzzle\Service\Description\XmlDescriptionBuilder;

class GithubClient extends Client
{
    /**
     * @var string Access Token
     */
    protected $accessToken;

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
            'scheme' => 'https'
        );
        $required = array('access_token', 'base_url');
        $config = Inspector::prepareConfig($config, $default, $required);

        $client = new self(
            $config->get('base_url'),
            $config->get('access_token')
        );
        $client->setConfig($config);

        // Uncomment the following two lines to use an XML service description
        $client->setDescription(XmlDescriptionBuilder::build(__DIR__ . DIRECTORY_SEPARATOR . 'client.xml'));

        return $client;
    }

    /**
     * Client constructor
     *
     * @param string $baseUrl Base URL of the web service
     * @param string $accessToken API Access Token
     */
    public function __construct($baseUrl, $accessToken)
    {
        parent::__construct($baseUrl);
        $this->accessToken = $accessToken;
    }
}