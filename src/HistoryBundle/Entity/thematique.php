<?php

namespace HistoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use HistoryBundle\Entity\personne;

/**
 * thematique
 *
 * @ORM\Table(name="thematique")
 * @ORM\Entity(repositoryClass="HistoryBundle\Repository\thematiqueRepository")
 */
class thematique
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
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\ManyToOne(targetEntity="HistoryBundle\Entity\thematiqueCategory")
     * @ORM\JoinColumn(name="thematique_category_id", referencedColumnName="id", nullable=true)
     */
    private $thematiqueCategory;

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
     * Set nom
     *
     * @param string $nom
     *
     * @return thematique
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }


    /**
     * Set thematiqueCategory
     *
     * @param \HistoryBundle\Entity\thematiqueCategory $thematiqueCategory
     *
     * @return thematique
     */
    public function setThematiqueCategory(\HistoryBundle\Entity\thematiqueCategory $thematiqueCategory = null)
    {
        $this->thematiqueCategory = $thematiqueCategory;

        return $this;
    }

    /**
     * Get thematiqueCategory
     *
     * @return \HistoryBundle\Entity\thematiqueCategory
     */
    public function getThematiqueCategory()
    {
        return $this->thematiqueCategory;
    }
}
