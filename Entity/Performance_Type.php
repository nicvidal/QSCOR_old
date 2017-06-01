<?php

namespace QSCORBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Performance_Type
 *
 * @ORM\Table(name="performance_type")
 * @ORM\Entity(repositoryClass="QSCORBundle\Repository\Performance_TypeRepository")
 */
class Performance_Type
{
    const COST = "Cost";
    const RESPONSIVENESS = "Responsiveness";
    const RELIABILITY = "Reliability";
    const AGILITY = "Agility";
    const ASSETMANAGEMENT = "Asset Management";
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
     * @ORM\Column(name="libelle_Performance", type="string",length = 255, nullable=true)
     */
    private $libellePerformance;

    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="performance_type")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $project;

    /**
     * @ORM\OneToMany(targetEntity="Performance", mappedBy="performance_type")
     */
    private $performance;


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
     * Set libellePerformance
     *
     * @param string $libellePerformance
     *
     * @return Performance_Type
     */
    public function setLibellePerformance($libellePerformance)
    {
        $this->libellePerformance = $libellePerformance;

        return $this;
    }

    /**
     * Get libellePerformance
     *
     * @return string
     */
    public function getLibellePerformance()
    {
        return $this->libellePerformance;
    }

    /**
     * Set project
     *
     * @param \QSCORBundle\Entity\Project $project
     *
     * @return Performance_Type
     */
    public function setProject(\QSCORBundle\Entity\Project $project = null)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return \QSCORBundle\Entity\Project
     */
    public function getProject()
    {
        return $this->project;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->performance = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add performance
     *
     * @param \QSCORBundle\Entity\Performance $performance
     *
     * @return Performance_Type
     */
    public function addPerformance(\QSCORBundle\Entity\Performance $performance)
    {
        $this->performance[] = $performance;

        return $this;
    }

    /**
     * Remove performance
     *
     * @param \QSCORBundle\Entity\Performance $performance
     */
    public function removePerformance(\QSCORBundle\Entity\Performance $performance)
    {
        $this->performance->removeElement($performance);
    }

    /**
     * Get performance
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPerformance()
    {
        return $this->performance;
    }
}
