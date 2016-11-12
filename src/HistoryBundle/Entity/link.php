<?php

namespace HistoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * link
 *
 * @ORM\Table(name="link")
 * @ORM\Entity(repositoryClass="HistoryBundle\Repository\linkRepository")
 */
class link
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
    private $from;

    /**
     * @ORM\ManyToOne(targetEntity="HistoryBundle\Entity\personne")
     */
    private $to;

    /**
     * @ORM\ManyToOne(targetEntity="HistoryBundle\Entity\linkType")
     */
    private $linkType;

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
     * Set from
     *
     * @param \HistoryBundle\Entity\personne $from
     *
     * @return link
     */
    public function setFrom(\HistoryBundle\Entity\personne $from = null)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Get from
     *
     * @return \HistoryBundle\Entity\personne
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Set to
     *
     * @param \HistoryBundle\Entity\personne $to
     *
     * @return link
     */
    public function setTo(\HistoryBundle\Entity\personne $to = null)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * Get to
     *
     * @return \HistoryBundle\Entity\personne
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Set linkType
     *
     * @param \HistoryBundle\Entity\linkType $linkType
     *
     * @return link
     */
    public function setLinkType(\HistoryBundle\Entity\linkType $linkType = null)
    {
        $this->linkType = $linkType;

        return $this;
    }

    /**
     * Get linkType
     *
     * @return \HistoryBundle\Entity\linkType
     */
    public function getLinkType()
    {
        return $this->linkType;
    }
}
