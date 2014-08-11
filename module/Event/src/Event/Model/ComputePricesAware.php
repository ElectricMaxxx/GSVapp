<?php


namespace Event\Model;

/**
 * Interface for objects that are aware of prices.
 *
 * It helps to check for a balance state, count the receivables
 * and the sum of all coasts.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
interface ComputePricesAware
{
    /**
     * @return int
     */
    public function getPriceInComplete();

    /**
     * Should answer whether an object is balanced or not.
     *
     * @return bool
     */
    public function isBalanced();

    /**
     * @return int
     */
    public function getReceivables();
}
