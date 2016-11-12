<?php

namespace HistoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * linkType
 *
 * @ORM\Table(name="link_type")
 * @ORM\Entity(repositoryClass="HistoryBundle\Repository\linkTypeRepository")
 */
class linkType
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
     * @ORM\ManyToOne(targetEntity="HistoryBundle\Entity\linkType")
     */
    private $reverseLink;


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
     * @return linkType
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
     * Set reverseLink
     *
     * @param \HistoryBundle\Entity\linkType $reverseLink
     *
     * @return linkType
     */
    public function setReverseLink(\HistoryBundle\Entity\linkType $reverseLink = null)
    {
        $this->reverseLink = $reverseLink;

        return $this;
    }

    /**
     * Get reverseLink
     *
     * @return \HistoryBundle\Entity\linkType
     */
    public function getReverseLink()
    {
        return $this->reverseLink;
    }
}
