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
    * @ORM\ManyToMany(targetEntity="HistoryBundle\Entity\personne", cascade={"persist"}, inversedBy="thematiques")
    * @ORM\JoinTable(name="thematique_personne",
    *      joinColumns={@ORM\JoinColumn(name="thematique_id", referencedColumnName="id")},
    *      inverseJoinColumns={@ORM\JoinColumn(name="personne_id", referencedColumnName="id")}
    * )
    */
    private $personnes;
    
    public function __construct()
    {
        $this->personnes = new ArrayCollection();
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
    
    public function addPersonne(Personne $personne)
    {
        $this->personnes[] = $personne;
        
        return $this;
    }

    public function removePersonne(Personne $personne)
    {
        $this->personnes->removeElement($personne);
    }

    public function getPersonnes()
    {
        return $this->personnes;
    }

}
