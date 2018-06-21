<?php

namespace App\Entity;

use Doctrine\ORM\PersistentCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;


/**
 * @ORM\Entity(repositoryClass="App\Repository\LeagueRepository")
 * @ORM\Table(options={"collate"="utf8_general_ci", "charset"="utf8"})
 * @JMS\ExclusionPolicy("all")
 */
class League
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @JMS\Expose
     * @JMS\Groups({"show"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @JMS\Expose
     * @JMS\Groups({"show"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Team", mappedBy="league")
     */
    protected $teams;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return League
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return null|PersistentCollection
     */
    public function getTeams(): ?PersistentCollection
    {
        return $this->teams;
    }
}
