<?php namespace Well\DBBundle\Hydrator;

/**
 * Hydrator interface
 */
interface HydratorInterface
{
    /**
     * Create and hydrate array values to object
     *
     * @param array $data Source data
     * @return mixed
     */
    public function create(array $data);

    /**
     * Hydrate array values to exists object
     *
     * @param object $object Object for hydrate
     * @param array $data Source data
     * @return void
     */
    public function hydrate($object, array $data);
}
