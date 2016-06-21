<?php

namespace HistoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use HistoryBundle\Entity\thematique;

/**
 * personne
 *
 * @ORM\Table(name="personne")
 * @ORM\Entity(repositoryClass="HistoryBundle\Repository\personneRepository")
 */
class personne
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
     * @var string
     *
     * @ORM\Column(name="ref", type="string", length=10, unique=true)
     */
    private $ref;

    /**
     * @var string
     *
     * @ORM\Column(name="wiki", type="string", length=255, unique=true)
     */
    private $wiki;

    /**
     * @var int
     *
     * @ORM\Column(name="gender", type="integer")
     */
    private $gender;
    
    /**
    * @ORM\ManyToMany(targetEntity="HistoryBundle\Entity\thematique", cascade={"persist"}, inversedBy="personnes")
    * @ORM\JoinTable(name="thematique_personne",
    *      joinColumns={@ORM\JoinColumn(name="personne_id", referencedColumnName="id")},
    *      inverseJoinColumns={@ORM\JoinColumn(name="thematiques_id", referencedColumnName="id")}
    * )
    */
    private $thematiques;
    
    public function __construct()
    {
        $this->thematiques = new ArrayCollection();
    }

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
     * @return personne
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
     * Set ref
     *
     * @param string $ref
     *
     * @return personne
     */
    public function setRef($ref)
    {
        $this->ref = $ref;

        return $this;
    }

    /**
     * Get ref
     *
     * @return string
     */
    public function getRef()
    {
        return $this->ref;
    }

    /**
     * Set wiki
     *
     * @param string $wiki
     *
     * @return personne
     */
    public function setWiki($wiki)
    {
        $this->wiki = $wiki;

        return $this;
    }

    /**
     * Get wiki
     *
     * @return string
     */
    public function getWiki()
    {
        return $this->wiki;
    }

    /**
     * Set gender
     *
     * @param int $gender
     *
     * @return personne
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return int
     */
    public function getGender()
    {
        return $this->gender;
    }
    
    public function addThematique(Thematique $thematique)
    {
      $this->thematiques[] = $thematique;

      return $this;
    }

    public function removeThematique(Thematique $thematique)
    {
      $this->thematiques->removeElement($thematique);
    }

    public function getThematiques()
    {
      return $this->thematiques;
    }
}
