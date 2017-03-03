<?php

namespace Stk\AdhesionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PresenceBC
 *
 * @ORM\Table(name="presence_b_c")
 * @ORM\Entity(repositoryClass="Stk\AdhesionBundle\Repository\PresenceBCRepository")
 */
class PresenceBC
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
     * @var Membre
     *
     * @ORM\ManyToOne(targetEntity="Stk\AdhesionBundle\Entity\Membre", inversedBy="presencesBc")
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
     * @return PresenceBC
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
     * Set membre
     *
     * @param \Stk\AdhesionBundle\Entity\Membre $membre
     *
     * @return PresenceBC
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
}
