<?php

namespace Stk\AdhesionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Membre
 *
 * @ORM\Table(name="membre")
 * @ORM\Entity(repositoryClass="Stk\AdhesionBundle\Repository\MembreRepository")
 */
class Membre
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=255)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=255, nullable=true)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, options={"default" : "c"} )
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="like_as", type="string", length=255, options={"default" : "s"})
     */
    private $likeAs;

    /**
     * @var Presence[]
     *
     * @ORM\OneToMany(targetEntity="Stk\AdhesionBundle\Entity\Presence", mappedBy="membre", cascade={"remove"})
     */
    private $presences;

    /**
     * @var PresenceBC[]
     *
     * @ORM\OneToMany(targetEntity="Stk\AdhesionBundle\Entity\PresenceBC", mappedBy="membre", cascade={"remove"})
     */
    private $presencesBc;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Membre
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Membre
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Membre
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set type
     *
     * @param string $status
     *
     * @return Membre
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->presences = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add presence
     *
     * @param \Stk\AdhesionBundle\Entity\Presence $presence
     *
     * @return Membre
     */
    public function addPresence(\Stk\AdhesionBundle\Entity\Presence $presence)
    {
        $this->presences[] = $presence;

        return $this;
    }

    /**
     * Remove presence
     *
     * @param \Stk\AdhesionBundle\Entity\Presence $presence
     */
    public function removePresence(\Stk\AdhesionBundle\Entity\Presence $presence)
    {
        $this->presences->removeElement($presence);
    }

    /**
     * Get presences
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPresences()
    {
        return $this->presences;
    }

    /**
     * Set likeAs
     *
     * @param string $likeAs
     *
     * @return Membre
     */
    public function setLikeAs($likeAs)
    {
        $this->likeAs = $likeAs;

        return $this;
    }

    /**
     * Get likeAs
     *
     * @return string
     */
    public function getLikeAs()
    {
        return $this->likeAs;
    }

    /**
     * Set id
     *
     * @param integer $id
     *
     * @return Membre
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Add presencesBc
     *
     * @param \Stk\AdhesionBundle\Entity\PresenceBC $presencesBc
     *
     * @return Membre
     */
    public function addPresencesBc(\Stk\AdhesionBundle\Entity\PresenceBC $presencesBc)
    {
        $this->presencesBc[] = $presencesBc;

        return $this;
    }

    /**
     * Remove presencesBc
     *
     * @param \Stk\AdhesionBundle\Entity\PresenceBC $presencesBc
     */
    public function removePresencesBc(\Stk\AdhesionBundle\Entity\PresenceBC $presencesBc)
    {
        $this->presencesBc->removeElement($presencesBc);
    }

    /**
     * Get presencesBc
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPresencesBc()
    {
        return $this->presencesBc;
    }
}
