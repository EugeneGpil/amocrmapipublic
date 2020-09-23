<?php
declare(strict_types=1);

namespace Amocrmapi\V2\Api;

use Amocrmapi\V2\Traits\InitApiTrait;
use Amocrmapi\V2\Traits\SingletonTrait;
use Amocrmapi\Exceptions\RequestException;
use Amocrmapi\Dependencies\EntityApiInterface;

/**
 * Class AccountApi
 *
 * @package Amocrmapi\V2\Api
 */
class AccountApi implements EntityApiInterface
{
	use SingletonTrait, InitApiTrait;

    /**
     * Api link adds to .amocrm.ru
     */
    const LINK = "/api/v2/account";

    /**
     * Get all info about account
     *
     * @throws RequestException
     *
     * @return array
     */
	public function getAccountInfo() : array
    {
        $data = $this->api->request(
            self::LINK,
            '?with=custom_fields,users,pipelines,groups,note_types,task_types',
            'GET'
        );
        
        if (!isset($data["current_user"])) {
            throw new RequestException($data["response"]["error"], (int) $data["response"]["code"]);
        }

        return $data["_embedded"];
    }
}
