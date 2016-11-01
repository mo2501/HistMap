<?php

namespace HistoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * suggestion
 *
 * @ORM\Table(name="suggestion")
 * @ORM\Entity(repositoryClass="HistoryBundle\Repository\suggestionRepository")
 */
class suggestion
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
     * @ORM\Column(name="person_name", type="string", length=255)
     * @Assert\NotBlank(message="Le nom ne doit pas être vide.")
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z0-9 \-ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ]*$/",
     *     match=true,
     *     message="Le nom est incorrect."
     * )
     */
    private $personName;

    /**
     * @var string
     *
     * @ORM\Column(name="place_name", type="string", length=255)
     * @Assert\NotBlank(message="Le lieu ne doit pas être vide.")
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z0-9 \-ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ]*$/",
     *     match=true,
     *     message="Le lieu est incorrect."
     * )
     */
    private $placeName;

    /**
     * @var int
     *
     * @ORM\Column(name="date", type="integer")
     * @Assert\NotBlank(message="L'année ne doit pas être vide.")
     * @Assert\Range(
     *      min = -3000,
     *      max = 2020,
     *      minMessage = "La date doit être supérieure à -3000.",
     *      maxMessage = "La date doit être inférieure à 2020.",
     *      invalidMessage = "La date doit être un nombre."
     * )
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="event_name", type="string", length=255)
     * @Assert\NotBlank(message="Le nom de l'événement ne doit pas être vide")
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z0-9 \-ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ]*$/",
     *     match=true,
     *     message="Le nom de l'événement est incorrect."
     * )
     */
    private $eventName;

    /**
     * @var string
     *
     * @ORM\Column(name="wiki", type="string", length=1000)
     * @Assert\NotBlank(message="Le lien Wikipédia ne doit pas être vide")
     * @Assert\Url(
     *    message = "Le lien Wikipédia est invalide.",
     * )
     * @Assert\Regex(
     *     pattern="/wikipedia.org/",
     *     match=true,
     *     message="Le lien Wikipédia est invalide."
     * )
     */
    private $wiki;


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
     * Set personName
     *
     * @param string $personName
     *
     * @return suggestion
     */
    public function setPersonName($personName)
    {
        $this->personName = $personName;

        return $this;
    }

    /**
     * Get personName
     *
     * @return string
     */
    public function getPersonName()
    {
        return $this->personName;
    }

    /**
     * Set placeName
     *
     * @param string $placeName
     *
     * @return suggestion
     */
    public function setPlaceName($placeName)
    {
        $this->placeName = $placeName;

        return $this;
    }

    /**
     * Get placeName
     *
     * @return string
     */
    public function getPlaceName()
    {
        return $this->placeName;
    }

    /**
     * Set date
     *
     * @param integer $date
     *
     * @return suggestion
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return int
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set eventName
     *
     * @param string $eventName
     *
     * @return suggestion
     */
    public function setEventName($eventName)
    {
        $this->eventName = $eventName;

        return $this;
    }

    /**
     * Get eventName
     *
     * @return string
     */
    public function getEventName()
    {
        return $this->eventName;
    }

    /**
     * Set wiki
     *
     * @param string $wiki
     *
     * @return suggestion
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
}
