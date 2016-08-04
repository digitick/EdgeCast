<?php
/**
 * EdgeCast API Client
 *
 * @author Digitick <dev@digitick.com>
 */
namespace Digitick\ThirdParty\EdgeCast;

use GuzzleHttp\ClientInterface as HttpClientInterface;

/**
 * EdgeCast API Client
 */
class Client implements ClientInterface
{
    /**
     * Default EdgeCast APi endpoit
     */
    const ENDPOINT = 'https://api.edgecast.com/v2/mcc/customers/%s/edge/';

    /**
     * Flash Media Streaming media type.
     */
    const MEDIA_TYPE_FLASH_MEDIA_STREAMING = 2;

    /**
     * HTTP Large Object media type.
     */
    const MEDIA_TYPE_HTTP_LARGE_OBJECT = 3;

    /**
     * HTTP Small Object media type.
     */
    const MEDIA_TYPE_HTTP_SMALL_OBJECT = 8;

    /**
     * HTTP Small Object media type.
     */
    const MEDIA_TYPE_APPLICATION_DELIVERY_NETWORK = 14;

    /**
     * Method name to purge a pattern
     */
    const METHOD_PURGE  =   'purge';

    /**
     * Method name to load a media
     */
    const METHOD_LOAD   =   'load';

    /**
     * @var string $http_client Instance of a Guzzle Client
     */
    private $http_client;

    /**
     * @var string $account EdgeCast account number
     */
    private $account;

    /**
     * @var string $token EdgeCast client token
     */
    private $token;

    /**
     * @var string $cdn_url Root URL of the EdgeCast account
     */
    private $cdn_url;

    /**
     * Creates the EdgeCast client
     *
     * @param HttpCLientInterface $http_client Instance of a Guzzle Client
     * @param string              $account     EdgeCast account number
     * @param string              $token       EdgeCast client token
     * @param url                 $cdn_url     Root URL of the EdgeCast account
     * @param url                 $endpoint    EdgeCast API endpoint to use
     */
    public function __construct(HttpClientInterface $http_client, $account, $token, $cdn_url, $endpoint = self::ENDPOINT)
    {
        $this->http_client = $http_client;
        $this->account = $account;
        $this->token = $token;
        $this->cdn_url = $cdn_url;
        $this->endpoint = sprintf($endpoint, $account);
    }

    /**
     * Purges the EdgeCast cache for a given pattern
     *
     * @param  int    $media_type
     * @param  string $pattern
     * @return bool   True if all medias matching the pattern have been purged
     */
    public function purge($media_type, $pattern)
    {
        $data = [
            'MediaType' => $media_type,
            'MediaPath' => $pattern
        ];

        return $this->request(self::METHOD_PURGE, $data);
    }

    /**
     * Preloads the EdgeCast cache for a given url
     *
     * @param  int    $media_type Numerical identifier for the type of media to load
     * @param  string $url        URL of the media to load
     * @return bool   True if the media has been loaded by EdgeCast
     */
    public function load($media_type, $url)
    {
        $data = [
            'MediaType' => $media_type,
            'MediaPath' => $url
        ];

        return $this->request(self::METHOD_LOAD, $data);
    }

    /**
     * Executes the HTTP request
     *
     * @param  string $method EdgeCast API method to call - purge or load
     * @param  array  $data
     * @return bool   Success of the HTTP request
     */
    private function request($method, array $data)
    {
        $headers = [
            'Authorization' =>  sprintf('TOK:%s', $this->token),
            'Accept'        =>  'application/json',
            'Content-Type'  =>  'application/json'
        ];

        try {
            $response = $this->http_client->put($this->endpoint . $method, [
                'headers' => $headers,
                'json' => $data
            ]);
        } catch (\Exception $e) {
            return false;
        }

        return 200 === $response->getStatusCode();
    }
}
