<?php

namespace QSCORBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Level
 *
 * @ORM\Table(name="level")
 * @ORM\Entity(repositoryClass="QSCORBundle\Repository\LevelRepository")
 */
class Level
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
     * @ORM\Column(name="libelle_level", type="string", length=255)
     */
    private $libelleLevel;

    /**
     * @ORM\ManyToOne(targetEntity="Site", inversedBy="level")
     * @ORM\JoinColumn(name="site_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $site;

    /**
     * @ORM\ManyToMany(targetEntity="Level_Type")
     * @ORM\JoinTable(name="level_type_level",
     *      joinColumns={@ORM\JoinColumn(name="level_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="level_type_id", referencedColumnName="id", onDelete="CASCADE")}
     *      )
     */
    private $levels_types;

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
     * Set libelleLevel
     *
     * @param string $libelleLevel
     *
     * @return Level
     */
    public function setLibelleLevel($libelleLevel)
    {
        $this->libelleLevel = $libelleLevel;

        return $this;
    }

    /**
     * Get libelleLevel
     *
     * @return string
     */
    public function getLibelleLevel()
    {
        return $this->libelleLevel;
    }

    /**
     * Set site
     *
     * @param \QSCORBundle\Entity\Site $site
     *
     * @return Level
     */
    public function setSite(\QSCORBundle\Entity\Site $site = null)
    {
        $this->site = $site;

        return $this;
    }

    /**
     * Get site
     *
     * @return \QSCORBundle\Entity\Site
     */
    public function getSite()
    {
        return $this->site;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->levels_types = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add levelsType
     *
     * @param \QSCORBundle\Entity\Level_Type $levelsType
     *
     * @return Level
     */
    public function addLevelsType(\QSCORBundle\Entity\Level_Type $levelsType)
    {
        $this->levels_types[] = $levelsType;

        return $this;
    }

    /**
     * Remove levelsType
     *
     * @param \QSCORBundle\Entity\Level_Type $levelsType
     */
    public function removeLevelsType(\QSCORBundle\Entity\Level_Type $levelsType)
    {
        $this->levels_types->removeElement($levelsType);
    }

    /**
     * Get levelsTypes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLevelsTypes()
    {
        return $this->levels_types;
    }
}
