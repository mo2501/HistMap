<?php

namespace HistoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * event
 *
 * @ORM\Table(name="event")
 * @ORM\Entity(repositoryClass="HistoryBundle\Repository\eventRepository")
 */
class event
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
     * @var string
     *
     * @ORM\Column(name="intitule", type="string", length=1000)
     */
    private $intitule;
    
    /**
     * @var \integer
     *
     * @ORM\Column(name="year", type="integer")
     */
    private $year;
    
    /**
    * @ORM\ManyToOne(targetEntity="HistoryBundle\Entity\personne")
    */
    private $personne;
    
    /**
    * @ORM\ManyToOne(targetEntity="HistoryBundle\Entity\place")
    */
    private $place;
    
    /**
     * @var \integer
     *
     * @ORM\Column(name="eventType", type="integer")
     */
    private $eventType;


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
     * Set intitule
     *
     * @param string $intitule
     *
     * @return event
     */
    public function setIntitule($intitule)
    {
        $this->intitule = $intitule;

        return $this;
    }

    /**
     * Get intitule
     *
     * @return string
     */
    public function getIntitule()
    {
        return $this->intitule;
    }

    /**
     * Set personne
     *
     * @param \HistoryBundle\Entity\personne $personne
     *
     * @return event
     */
    public function setPersonne(\HistoryBundle\Entity\personne $personne = null)
    {
        $this->personne = $personne;

        return $this;
    }

    /**
     * Get personne
     *
     * @return \HistoryBundle\Entity\personne
     */
    public function getPersonne()
    {
        return $this->personne;
    }

    /**
     * Set place
     *
     * @param \HistoryBundle\Entity\place $place
     *
     * @return event
     */
    public function setPlace(\HistoryBundle\Entity\place $place = null)
    {
        $this->place = $place;

        return $this;
    }

    /**
     * Get place
     *
     * @return \HistoryBundle\Entity\place
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Set year
     *
     * @param integer $year
     *
     * @return event
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return integer
     */
    public function getYear()
    {
        return $this->year;
    }
    
    /**
     * Set eventType
     *
     * @param integer $eventType
     *
     * @return event
     */
    public function setEventType($eventType)
    {
        $this->eventType = $eventType;

        return $this;
    }

    /**
     * Get eventType
     *
     * @return integer
     */
    public function getEventType()
    {
        return $this->eventType;
    }
}
