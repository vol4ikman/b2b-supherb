<?php

namespace PriorityApi;

use Exception;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\ClientInterface;
use PriorityApi\Builder\Builder;

/**
 * Class PriorityClient
 *
 * Priority documentation
 * https://prioritysoftware.github.io/restapi/
 *
 * @package PriorityApi
 */
class PriorityClient
{
    /**
     * Service root URL appropriate to your installation of Priority.
     *
     * @var string
     */
    protected $serviceRootUrl;

    /**
     * Auth
     *
     * @var array = ['type' => 'basic', 'credentials' => []]
     */
    protected $auth;

    /**
     * Should we request full metadata
     *
     * @var bool
     */
    protected $withFullMetadata = false;

    /**
     * Should we verify the service's ssl certificate
     *
     * @var bool
     */
    protected $verifySsl = true;


    /**
     * PriorityClient constructor.
     *
     * @param string|ClientInterface $serviceRootUrl
     */
    public function __construct($serviceRootUrl)
    {
        $this->serviceRootUrl = $serviceRootUrl;
    }

    /**
     * @param string $username
     * @param string $password
     *
     * @return $this
     */
    public function withBasicAuth(string $username, string $password)
    {
        $this->auth = [
            'type'        => 'basic',
            'credentials' => [
                'username' => $username,
                'password' => $password,
            ],
        ];

        return $this;
    }

    /**
     * @param string $appId
     * @param string $appKey
     *
     * @return $this
     */
    protected function withAppAuth(string $appId, string $appKey)
    {
        $this->auth = [
            'type'        => 'app',
            'credentials' => [
                'app_id'  => $appId,
                'app_key' => $appKey,
            ],
        ];

        return $this;
    }

    public function withFullMetadata()
    {
        $this->withFullMetadata = true;
        return $this;
    }

    public function withoutFullMetadata()
    {
        $this->withFullMetadata = false;
        return $this;
    }

    /**
     * Should we verify the service's ssl certificate
     *
     * @param bool $value
     *
     * @return $this
     */
    public function verifySsl(bool $value)
    {
        $this->verifySsl = $value;
        return $this;
    }

    protected function getHttpClient()
    {
        $config = [
            'base_uri' => $this->serviceRootUrl,
            'verify'   => $this->verifySsl,
            'headers'  => [],
        ];

        // Handle authentication
        if (isset($this->auth['type']) && isset($this->auth['credentials'])) {
            switch ($this->auth['type']) {
                case 'basic':
                    $config['auth'] = [
                        $this->auth['credentials']['username'],
                        $this->auth['credentials']['password'],
                    ];
                    break;

                case 'app':
                    $config['headers']['X-App-Id'] = $this->auth['credentials']['app_id'];
                    $config['headers']['X-App-Key'] = $this->auth['credentials']['app_key'];
                    break;
            }
        }

        // Handle metadata
        if ($this->withFullMetadata) {
            $config['headers']['Accept'] = 'application/json;odata.metadata=full';
        }

        $client = new HttpClient($config);

        return $client;
    }

    /**
     * @param Builder|string $query
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function get($query)
    {
        $result = $this->getHttpClient()->get((string)$query);

        return $result;
    }

    /**
     * @return array = ['entities' => []]
     * @throws Exception
     */
    public function getMetadata()
    {
        $result = $this->getHttpClient()->get('$metadata');

        if (
            $result->getHeader('Content-Type')[0]
            !== 'application/xml'
        ) {
            throw new Exception('Unexpected response');
        }

        $xml = simplexml_load_string((string)$result->getBody());

        $ns = $xml->getNamespaces(true);

        $entities = [];

        $schema = $xml->children($ns['edmx'])->children();

        foreach ($schema->children() as $name => $child) {
            switch ($name) {
                case 'EntityType':

                    $name = (string)$child['Name'];

                    $entities[$name] = [
                        'name'       => $name,
                        'key'        => (string)$child->Key->PropertyRef['Name'],
                        'properties' => [],
                    ];

                    foreach ($child->Property as $property) {
                        $pName = (string)$property['Name'];
                        $entities[$name]['properties'][$pName] = [
                            'name' => $pName,
                            'type' => (string)$property['Type'],
                        ];
                    }

                    foreach ($child->NavigationProperty as $subform) {
                        $subFormName = (string)$subform['Name'];
                        $entities[$name]['sub_forms'][$subFormName] = [
                            'name' => $name,
                            'type' => (string)$subform['Type'],
                        ];
                    }
                    break;
            }
        }

        $metadata['entities'] = $entities;

        return $metadata;
    }
}