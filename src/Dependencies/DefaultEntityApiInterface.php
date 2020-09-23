<?php
declare(strict_types=1);

namespace Amocrmapi\Dependencies;

/**
 * Interface DefaultEntityApiInterface
 *
 * @package Amocrmapi\Dependencies
 */
interface DefaultEntityApiInterface extends EntityApiInterface
{
    /**
     * @param array $entities
     *
     * @return array
     */
    public function add(array $entities) : array;

    /**
     * @param array $params
     *
     * @return array
     */
    public function get(array $params) : array;

    /**
     * @param array $entities
     *
     * @return array
     */
    public function update(array $entities) : array;

    /**
     * @param array $entities
     *
     * @return array
     */
    public function remove(array $entities) : array;
}