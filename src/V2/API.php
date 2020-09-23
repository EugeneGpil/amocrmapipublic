<?php
// modified
declare(strict_types=1);

namespace Amocrmapi\V2;

use Amocrmapi\Dependencies\EntityApiInterface;
use Amocrmapi\Dependencies\EntityInterface;
use Amocrmapi\Exceptions\AuthException;
use Amocrmapi\V2\Helpers\Client;

/**
 * Main api class
 */
class API
{
	/**
	 * Amocrm subdomain - https://{$subdomain}.amocrm.ru
	 *
	 * @var string
	 */
	private $subdomain;

	/**
	 * Amocrm login - email address
	 *
	 * @var string
	 */
	private $login;

	/**
	 * Amocrm secret hash - AmoCRM -> settings -> API -> Your api key
	 *
	 * @var string
	 */
	private $hash;
	private $access_token;
	private $refresh_token;
	private $access_token_expires_at;
	private $app_id;
	private $app_secret;
	private $app_redirect_url;

    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
	 * @param $subdomain string
	 * @param $login string
	 * @param $hash string
	 */
	public function __construct(array $data)
	{
		$this->subdomain = isset($data["subdomain"]) ? $data["subdomain"] : null;;
		$this->login = isset($data["login"]) ? $data["login"] : null;
		$this->hash = isset($data["hash"]) ? $data["hash"] : null;
		$this->access_token = isset($data["access_token"]) ? $data["access_token"] : null;
		$this->refresh_token = isset($data["refresh_token"]) ? $data["refresh_token"] : null;
		$this->access_token_expires_at = isset($data["access_token_expires_at"])
			? $data["access_token_expires_at"] : null;
		$this->app_id = isset($data["app_id"]) ? $data["app_id"] : null;
		$this->app_secret = isset($data["app_secret"]) ? $data["app_secret"] : null;
		$this->app_redirect_url = isset($data["app_redirect_url"]) ? $data["app_redirect_url"] : null;
	}

    /**
     * Try connect to amocrm
     *
     * @throws AuthException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return API
     */
	public function connect($mode = "oAuth2.0", $onlySelectedMode = false)
	{
		$modes = [$mode];

		if (!$onlySelectedMode) {
			$modes[] = $mode == "apiKey" ? "oAuth2.0" : "apiKey";
		}

		foreach ($modes as $foreachMode) {

			if ($foreachMode == "apiKey") {

				$data = [
					'USER_LOGIN' =>  $this->login,
					'USER_HASH' => $this->hash
				];

				$this->client = new Client();

				$response = $this->request("/private/api/auth.php?type=json", $data);

				if (isset($response["response"]["auth"]) && $response["response"]["auth"]) {

					return $this;
				}

				if (
					(!isset($response["response"]["auth"])
					|| (isset($response["response"]["auth"]) && !$response["response"]["auth"]))
					&& $onlySelectedMode
				) {
					throw new AuthException(
						$response["response"]["error"],
						(int) $response["response"]["error_code"]
					);
				}

			} else if ($foreachMode == "oAuth2.0") {

				if (
					!$this->access_token || !$this->refresh_token || !$this->access_token_expires_at
					|| !$this->app_redirect_url
				) {
					if ($onlySelectedMode) {
						return null;
					} else {
						continue;
					}
				}

				if (time() > $this->access_token_expires_at) {

					$this->client = new Client();

					if ($this->refreshAccessToken()) {

						$this->client = new Client([
							"headers" => [
								"Authorization" => " Bearer " . $this->access_token
							]
						]);

						return $this;
					} else if ($onlySelectedMode) {
						return null;
					}

				} else {

					$this->client = new Client([
						"headers" => [
							"Authorization" => " Bearer " . $this->access_token
						]
					]);

					return $this;
				}
			}
		}

		return null;
	}

	private function refreshAccessToken() {
		$data = [
			"client_id" => $this->app_id,
			"client_secret" => $this->app_secret,
			"grant_type" => "refresh_token",
			"refresh_token" => $this->refresh_token,
			"redirect_url" => $this->app_redirect_url
		];

		$response = $this->request("/oauth2/access_token", $data);

		if (
			!isset($response["access_token"]) || !isset($response["refresh_token"])
			|| !isset($response["expires_in"])
		) {
			return false;
		}

		$this->access_token = $response["access_token"];
		$this->refresh_token = $response["refresh_token"];
		$this->access_token_expires_at = time() + $response["expires_in"] - 30;

		return true;
	}

    /**
     * Send request to amocrm
     *
     * @param string $uri
     * @param array|string $params
     * @param string $method
     * @param bool $hash - add hash to link
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws TooManyRequestsException
     * @throws AccountBlocked
     *
     * @return array
     */
	public function request(
		string $uri,
		$params,
		string $method = "POST",
		bool $hash = false
	) : array
	{
		$uri = "https://{$this->subdomain}.amocrm.ru{$uri}";

		if ($hash) $uri .= "?login={$this->login}&api_key={$this->hash}";

		return $this->client->request($uri, $params, $method);
	}

	/**
	 * Entity fabric method
	 * 
	 * @param string $entity
	 * 
	 * @return \Amocrmapi\Dependencies\EntityInterface
	 */
	public function create(string $entity) : EntityInterface
	{
		$entity = "\Amocrmapi\Entity\\" . ucfirst(strtolower($entity));

		return new $entity;
	}

	/**
	 * Get instance of api by type
	 * 
	 * @param string $type
	 * 
	 * @return \Amocrmapi\Dependencies\DefaultEntityApiInterface
	 */
	public function get(string $type) : EntityApiInterface
	{
		$apiType = "\Amocrmapi\V2\Api\\" . ucfirst(
			str_replace(
				"api",
				"Api",
				strtolower($type)
			)
		);

		return $apiType::getInstance()->init($this);
	}

	public function getAccessToken()
	{
		return $this->access_token;
	}

	public function getRefreshToken()
	{
		return $this->refresh_token;
	}

	public function getAccessTokenExpiresAt()
	{
		return $this->access_token_expires_at;
	}
}