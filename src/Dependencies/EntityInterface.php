<?php
declare(strict_types=1);

namespace Amocrmapi\Dependencies;


/**
 * Interface EntityInterface
 *
 * @package Amocrmapi\Dependencies
 */
interface EntityInterface
{
    /**
     * @return array
     */
    public function prepare() : array;

    /**
     * @param array $data
     *
     * @return mixed
     */
    public function parse(array $data);
}