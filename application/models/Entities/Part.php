<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * Part
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Part
{
    /**
     * @ORM\ManyToOne(targetEntity="Car", inversedBy="passengers")
     * @ORM\JoinColumn(name="car_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $car;
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Part
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function formattedPrice()
    {
        return $this->getPrice();
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Part
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get car
     *
     * @return \Entities\Car
     */
    public function getCar()
    {
        return $this->car;
    }

    /**
     * Set car
     *
     * @param \Entities\Car $car
     *
     * @return Part
     */
    public function setCar(\Entities\Car $car = null)
    {
        $this->car = $car;

        return $this;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
