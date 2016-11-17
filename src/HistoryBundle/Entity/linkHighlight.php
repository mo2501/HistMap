<?php

namespace HistoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * linkHighlight
 *
 * @ORM\Table(name="link_highlight")
 * @ORM\Entity(repositoryClass="HistoryBundle\Repository\linkHighlightRepository")
 */
class linkHighlight
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
     * @ORM\ManyToOne(targetEntity="HistoryBundle\Entity\personne")
     */
    private $personne;


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
     * Set personne
     *
     * @param \HistoryBundle\Entity\personne $personne
     *
     * @return linkHighlight
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
}
