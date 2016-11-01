<?php

namespace HistoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * thematiquePersonne
 *
 * @ORM\Table(name="thematique_personne")
 * @ORM\Entity(repositoryClass="HistoryBundle\Repository\thematiquePersonneRepository")
 */
class thematiquePersonne
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
     * @ORM\ManyToOne(targetEntity="HistoryBundle\Entity\thematique")
     */
    private $thematique;

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
     * @return thematiquePersonne
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
     * Set thematique
     *
     * @param \HistoryBundle\Entity\thematique $thematique
     *
     * @return thematiquePersonne
     */
    public function setThematique(\HistoryBundle\Entity\thematique $thematique = null)
    {
        $this->thematique = $thematique;

        return $this;
    }

    /**
     * Get thematique
     *
     * @return \HistoryBundle\Entity\thematique
     */
    public function getThematique()
    {
        return $this->thematique;
    }
}
