<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * Passenger
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Passenger
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
     * @ORM\Column(name="firstname", type="string", length=255)
     */
    private $first_name;
    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255)
     */
    private $last_name;
    /**
     * @var string
     *
     * @ORM\Column(name="bio", type="text", nullable=true)
     *
     */
    private $bio;

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Passenger
     */
    public function setFirstName($firstName)
    {
        $this->first_name = $firstName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Passenger
     */
    public function setLastName($lastName)
    {
        $this->last_name = $lastName;

        return $this;
    }

    /**
     * Get bio
     *
     * @return string
     */
    public function getBio()
    {
        return $this->bio;
    }

    /**
     * Set bio
     *
     * @param string $bio
     *
     * @return Passenger
     */
    public function setBio($bio)
    {
        $this->bio = $bio;

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
     * @return Passenger
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
