<?php
declare(strict_types=1);

namespace Amocrmapi\V2\Helpers;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Cookie\CookieJar;
use Amocrmapi\Exceptions\TooManyRequestsException;
use Amocrmapi\Exceptions\AccountBlockedException;
use Amocrmapi\Exceptions\EmptyResponseException;

/**
 * Helper for work with Guzzle
 */
class Client
{

	/**
	 * Http guzzle client
	 *
	 * @var GuzzleClient
	 */
	private $client;

	/**
	 * AmoCrm cookies
	 *
	 * @var CookieJar
	 */
	private $cookies;

	private $headers = [
		"User-Agent" => "Freshcube-Client/1.0",
		"Accept"     => "application/json",
	];

	/**
     * HTTPClient constructor.
     */
	public function __construct($params = [])
	{
		$this->cookies = new CookieJar;
		$this->client = new GuzzleClient(["cookies" => $this->cookies]);
		
		if (isset($params["headers"])) {
			$this->headers = array_merge($this->headers, $params["headers"]);
		}
	}

    /**
     * Send request
     *
     * @param string $uri
     * @param mixed $params
     * @param string $method
     *
     * @throws EmptyResponseException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return array
     */
	public function request(string $uri, $params, string $method) : array
	{
		$body = [
			"headers" => $this->headers,
			"verify" => false,
			"cookies" => $this->cookies,
		];
		$method === "GET" ? $uri .= $params : $body["form_params"] = $params;

		try {
			$response = $this->client->request($method, $uri, $body);
			$result = json_decode($response->getBody()->getContents(), true);
		} catch (\GuzzleHttp\Exception\ClientException $exception) {
        	$result = json_decode($exception->getResponse()->getBody()->getContents(), true);
		}
		
		if (isset($response)) {
			switch ($response->getStatusCode()) {
				case 429:
					throw new TooManyRequestsException;
					break;

				case 403:
					throw new AccountBlockedException;
					break;
			}
		}

        if (is_null($result)) throw new EmptyResponseException;

		return $result;
	}
}
