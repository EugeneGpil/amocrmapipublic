<?php
declare(strict_types=1);

namespace Amocrmapi\V2\Traits;

use Amocrmapi\Exceptions\RequestException;

/**
 * Default api methods
 */
trait DefaultApiMethodsTrait
{
	/**
     * Add entities to amocrm
     * 
     * @param array $entities
     * 
     * @throws RequestException
     * 
     * @return array
     */
    public function add(array $entities) : array
    {
        $data = [];
    	foreach ($entities as $entity) {
            $data["add"][] = $entity->prepare();
        }
        
        $data = $this->api->request(self::LINK, $data, 'POST');
        
        if (isset($data["title"]) && $data["title"] === "Error") {
            throw new RequestException($data["detail"], (int) $data["status"]);
        }

        if (isset($data["_embedded"]["errors"]) && !empty($data["_embedded"]["errors"])) {
            throw new RequestException(json_encode($data["_embedded"]["errors"]), 400);
        }

        if (!isset($data["_embedded"]["items"])) {
            throw new RequestException($data["response"]["error"], (int) $data["response"]["code"]);
        }

        return $data["_embedded"]["items"];
    }

    /**
     * Update amocrm entities
     * 
     * @param array $entities
     *
     * @throws RequestException
     *
     * @return array
     */
    public function update(array $entities) : array
    {
        $data = [];
        foreach ($entities as $entity) {
            $data["update"][] = $entity->prepare();
        }

        $data = $this->api->request(self::LINK, $data, 'POST');

        if (!isset($data['_embedded']['items'])) {
            throw new RequestException($data["response"]["error"], (int) $data["response"]["code"]);
        }

        if (isset($data["_embedded"]["errors"]) && !empty($data["_embedded"]["errors"])) {
            throw new RequestException(json_encode($data["_embedded"]["errors"]), 400);
        }

        return $data["_embedded"]["items"];
    }

    /**
     * Get entities from amocrm
     *
     * @param array $params
     *
     * @throws RequestException
     *
     * @return array
     */
    public function get(array $params) : array
    {
    	$entity = self::ENTITY_CLASS;
        $offset = 0;
        $entities = [];
        $params = http_build_query($params);

        while (true) {
            $data = $this->api->request(
                self::LINK,
                "?limit_rows=500&limit_offset={$offset}&{$params}",
                'GET'
            );

            if (!isset($data['_embedded']['items'])) {
                throw new RequestException($data["response"]["error"], (int) $data["response"]["code"]);
            }

            if (isset($data["_embedded"]["errors"]) && !empty($data["_embedded"]["errors"])) {
                throw new RequestException(json_encode($data["_embedded"]["errors"]), 400);
            }

            foreach ($data['_embedded']['items'] as $item) {
                $entities[] = (new $entity)->parse($item);
            }

            if (sizeof($data['_embedded']['items']) >= 500) {
                $offset += 500;
            } else break;
        }

        return $entities;
    }

    /**
     * Remove entities form amocrm
     * 
     * @todo research and realize remove method
     * 
     * @param array $params
     * 
     * @return array
     */
    public function remove(array $params) : array
    {
        return ["status" => "TODO"];
    }
}