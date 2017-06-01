<?php

namespace QSCORBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Type_Facility
 *
 * @ORM\Table(name="type_facility")
 * @ORM\Entity(repositoryClass="QSCORBundle\Repository\Type_FacilityRepository")
 * @UniqueEntity("name")
 */
class Type_Facility
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;


    /**
     * @ORM\OneToMany(targetEntity="Site", mappedBy="facility")
     */
    private $sites;

    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="facilities")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $project;

    public function __construct()
    {
        $this->sites = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return Type_Facility
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add site
     *
     * @param \QSCORBundle\Entity\Site $site
     *
     * @return Type_Facility
     */
    public function addSite(\QSCORBundle\Entity\Site $site)
    {
        $this->sites[] = $site;

        return $this;
    }

    /**
     * Remove site
     *
     * @param \QSCORBundle\Entity\Site $site
     */
    public function removeSite(\QSCORBundle\Entity\Site $site)
    {
        $this->sites->removeElement($site);
    }

    /**
     * Get sites
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSites()
    {
        return $this->sites;
    }
    /* THis For My Select Option in Select 2 :)*/

    public function __toString()
    {
        // TODO: Implement __toString() method.

        return $this->getName();
    }

    /**
     * Set project
     *
     * @param \QSCORBundle\Entity\Project $project
     *
     * @return Type_Facility
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
}
