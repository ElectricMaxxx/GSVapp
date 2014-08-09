<?php


namespace Event\Doctrine\Orm;

use Doctrine\ORM\Mapping as ORM;
use Event\Model\ExchangeArrayInterface;

/**
 * A meal is a part of a consumption with a specific price.
 * @ORM\Entity
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class Meal implements ExchangeArrayInterface
{
    /**
     * The primary key for the persistence.
     *
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * Price of a meal in euro cents.
     *
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * A name of a meal.
     *
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * An optional description or message.
     *
     * @var string
     *
     * @ORM\Column(type="text")
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

    /**
     * {@inheritDoc}
     */
    public function exchangeArray(array $data)
    {
        foreach ($data as $key => $value) {
            if (method_exists($this, 'set'.ucfirst($key))) {
                $this->{'set'.ucfirst($key)}($value);
            }
        }
    }
}
