<?php

namespace Stk\AdhesionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Presence
 *
 * @ORM\Table(name="presence")
 * @ORM\Entity(repositoryClass="Stk\AdhesionBundle\Repository\PresenceRepository")
 */
class Presence
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var integer
     *
     * @ORM\Column(name="lite_time", type="integer")
     */
    private $lateTime;
    
    /**
     * @var Membre
     *
     * @ORM\ManyToOne(targetEntity="Stk\AdhesionBundle\Entity\Membre", inversedBy="presences")
     * @ORM\JoinColumn(nullable=false)
     */
    private $membre;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Presence
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set time
     *
     * @param \DateTime $time
     *
     * @return Presence
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set membre
     *
     * @param \Stk\AdhesionBundle\Entity\Membre $membre
     *
     * @return Presence
     */
    public function setMembre(\Stk\AdhesionBundle\Entity\Membre $membre)
    {
        $this->membre = $membre;

        return $this;
    }

    /**
     * Get membre
     *
     * @return \Stk\AdhesionBundle\Entity\Membre
     */
    public function getMembre()
    {
        return $this->membre;
    }

    /**
     * Set lateTime
     *
     * @param integer $lateTime
     *
     * @return Presence
     */
    public function setLateTime($lateTime)
    {
        $this->lateTime = $lateTime;

        return $this;
    }

    /**
     * Get lateTime
     *
     * @return integer
     */
    public function getLateTime()
    {
        return $this->lateTime;
    }
}
