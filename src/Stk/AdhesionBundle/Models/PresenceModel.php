<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 3/14/17
 * Time: 8:49 AM
 */

namespace Stk\AdhesionBundle\Models;


use Stk\AdhesionBundle\Entity\Membre;
use Symfony\Component\Validator\Constraints\DateTime;

class PresenceModel
{
    /**
     * @var boolean
     */
    private $isKnow;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var \DateTime
     */
    private $arrivedAt;

    /**
     * @var \DateTime
     */
    private $startAt;

    /**
     * @var string
     */
    private $presenceType;

    /**
     * @var Membre
     */
    private $membre;

    /**
     * @return boolean
     */
    public function isIsKnow()
    {
        return $this->isKnow;
    }

    /**
     * @param boolean $isKnow
     */
    public function setIsKnow($isKnow)
    {
        $this->isKnow = $isKnow;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return \DateTime
     */
    public function getArrivedAt()
    {
        return $this->arrivedAt;
    }

    /**
     * @param \DateTime $arrivedAt
     */
    public function setArrivedAt($arrivedAt)
    {
        $this->arrivedAt = $arrivedAt;
    }

    /**
     * @return \DateTime
     */
    public function getStartAt()
    {
        return $this->startAt;
    }

    /**
     * @param \DateTime $startAt
     */
    public function setStartAt($startAt)
    {
        $this->startAt = $startAt;
    }

    /**
     * @return string
     */
    public function getPresenceType()
    {
        return $this->presenceType;
    }

    /**
     * @param string $presenceType
     */
    public function setPresenceType($presenceType)
    {
        $this->presenceType = $presenceType;
    }

    /**
     * @return Membre
     */
    public function getMembre()
    {
        return $this->membre;
    }

    /**
     * @param Membre $membre
     */
    public function setMembre($membre)
    {
        $this->membre = $membre;
    }

}