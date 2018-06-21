<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Exists;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TeamRepository")
 * @ORM\Table(options={"collate"="utf8_general_ci", "charset"="utf8"})
 * @JMS\ExclusionPolicy("all")
 */
class Team
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @JMS\Groups({"show"})
     * @JMS\Expose
     * @JMS\ReadOnly
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @JMS\Groups({"insert", "show", "update"})
     * @JMS\Expose
     * @Assert\NotBlank(groups={"insert, update"})
     * @Assert\Length(min = 2, max = 255, groups={"insert, update"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @JMS\Groups({"insert", "show", "update"})
     * @JMS\Expose
     * @Assert\NotBlank(groups={"insert, update"})
     * @Assert\Length(min = 2, max = 255, groups={"insert, update"})
     */
    private $strip;

    /**
     * @ORM\ManyToOne(targetEntity="League", fetch="EAGER", inversedBy="teams")
     * @ORM\JoinColumn(name="league_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     * @JMS\Expose
     * @JMS\Groups({"with-league", "update", "insert"})
     */
    protected $league;


    /**
     * @return null|integer
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
     * @return Team
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getStrip(): ?string
    {
        return $this->strip;
    }

    /**
     * @param string $strip
     * @return Team
     */
    public function setStrip(string $strip): self
    {
        $this->strip = $strip;

        return $this;
    }

    /**
     * @return null|League
     */
    public function getLeague(): ?League
    {
        return $this->league;
    }

    /**
     * @param $league
     * @return Team
     */
    public function setLeague($league): self
    {
        $this->league = $league;
        return $this;
    }
}
