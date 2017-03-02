<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 3/2/17
 * Time: 3:15 PM
 */

namespace Stk\AdhesionBundle\Models;


class EtatPresence
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
     * @var int
     */
    private $nbLate;

    /**
     * @var int
     */
    private $note;

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

    /**
     * @return int
     */
    public function getNbLate()
    {
        return $this->nbLate;
    }

    /**
     * @param int $nbLate
     */
    public function setNbLate($nbLate)
    {
        $this->nbLate = $nbLate;
    }

    /**
     * @return int
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param int $note
     */
    public function setNote($note)
    {
        $this->note = $note;
    }
    
    
    
}