<?php


namespace Event\Doctrine\Orm;

/**
 * A meal is a part of a consumption with a specific price.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class Meal
{
    /**
     * The primary key for the persistence.
     *
     * @var int
     */
    private $id;

    /**
     * Price of a meal in euro cents.
     *
     * @var int
     */
    private $price;

    /**
     * A name of a meal.
     *
     * @var string
     */
    private $name;

    /**
     * An optional description or message.
     *
     * @var string
     */
    private $description;

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param int $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }
}
