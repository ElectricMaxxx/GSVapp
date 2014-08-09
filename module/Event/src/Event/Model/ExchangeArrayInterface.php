<?php


namespace Event\Model;

/**
 * An interface to force models to create the mapping method from
 * array to the properties.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
interface ExchangeArrayInterface
{
    /**
     * The given data should be mapped to the entities properties.
     *
     * @param array $data
     */
    public function exchangeArray(array $data);

    /**
     * Helper method for the form to get the properties of an
     * entity as an array.
     *
     * @return array
     */
    public function getArrayCopy();
}
