<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * CronRule
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Car
{
    /**
     * @ORM\OneToMany(targetEntity="Passenger", mappedBy="car")
     */
    protected $passengers;
    /**
     * @ORM\OneToMany(targetEntity="Part", mappedBy="car")
     */
    protected $parts;
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
     * @var string
     *
     * @ORM\Column(name="make", type="string", length=255, nullable=true)
     */
    private $make;
    /**
     * @var string
     *
     * @ORM\Column(name="model", type="string", length=255, nullable=true)
     */
    private $model;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="year", type="datetime", nullable=true)
     */
    private $year;

    public function __construct()
    {
        $this->passengers = new ArrayCollection();
        $this->parts = new ArrayCollection();
    }

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
     * @return Car
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get make
     *
     * @return string
     */
    public function getMake()
    {
        return $this->make;
    }

    /**
     * Set make
     *
     * @param string $make
     *
     * @return Car
     */
    public function setMake($make)
    {
        $this->make = $make;

        return $this;
    }

    /**
     * Get model
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set model
     *
     * @param string $model
     *
     * @return Car
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get year
     *
     * @return \DateTime
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set year
     *
     * @param \DateTime $year
     *
     * @return Car
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Add passenger
     *
     * @param \Entities\Passenger $passenger
     *
     * @return Car
     */
    public function addPassenger(\Entities\Passenger $passenger)
    {
        $this->passengers[] = $passenger;

        return $this;
    }

    /**
     * Remove passenger
     *
     * @param \Entities\Passenger $passenger
     */
    public function removePassenger(\Entities\Passenger $passenger)
    {
        $this->passengers->removeElement($passenger);
    }

    /**
     * Get passengers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPassengers()
    {
        return $this->passengers;
    }

    /**
     * Add part
     *
     * @param \Entities\Part $part
     *
     * @return Car
     */
    public function addPart(\Entities\Part $part)
    {
        $this->parts[] = $part;

        return $this;
    }

    /**
     * Remove part
     *
     * @param \Entities\Part $part
     */
    public function removePart(\Entities\Part $part)
    {
        $this->parts->removeElement($part);
    }

    /**
     * Get parts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParts()
    {
        return $this->parts;
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
