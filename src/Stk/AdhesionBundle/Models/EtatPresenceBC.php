<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 3/3/17
 * Time: 1:49 PM
 */

namespace Stk\AdhesionBundle\Models;


class EtatPresenceBC
{
    /*
     * @var int
     */
    private $idMembre;

    /**
     * @var string
     */
    private $fullName;

    /**
     * @var int
     */
    private $nbPresence;

    /**
     * @return mixed
     */
    public function getIdMembre()
    {
        return $this->idMembre;
    }

    /**
     * @param mixed $idMembre
     */
    public function setIdMembre($idMembre)
    {
        $this->idMembre = $idMembre;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @param string $fullName
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
    }

    /**
     * @return int
     */
    public function getNbPresence()
    {
        return $this->nbPresence;
    }

    /**
     * @param int $nbPresence
     */
    public function setNbPresence($nbPresence)
    {
        $this->nbPresence = $nbPresence;
    }
    
    
}