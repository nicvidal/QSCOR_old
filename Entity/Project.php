<?php

namespace QSCORBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Project
 *
 * @ORM\Table(name="project")
 * @ORM\Entity(repositoryClass="QSCORBundle\Repository\ProjectRepository")
 * @UniqueEntity("name")
 */
class Project
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
     * 
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="datecreation", type="string", length=255)
     */
    private $datecreation;

    /**
     * @ORM\OneToMany(targetEntity="Company", mappedBy="project")
     */

    private $companies;

    /**
     * @ORM\OneToMany(targetEntity="Performance_Type", mappedBy="project")
     */

    private $performance_type;


    /**
     * @ORM\OneToMany(targetEntity="Type_Facility", mappedBy="project")
     */

    private $facilities;

    /**
     * @ORM\OneToMany(targetEntity="Flux", mappedBy="project")
     */

    private $flux;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="projects")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $user;

    public function __construct()
    {
        $this->companies = new ArrayCollection();
        $this->facilities = new ArrayCollection();
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
     * @return Project
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
     * Add company
     *
     * @param \QSCORBundle\Entity\Company $company
     *
     * @return Project
     */
    public function addCompany(\QSCORBundle\Entity\Company $company)
    {
        $this->companies[] = $company;

        return $this;
    }

    /**
     * Remove company
     *
     * @param \QSCORBundle\Entity\Company $company
     */
    public function removeCompany(\QSCORBundle\Entity\Company $company)
    {
        $this->companies->removeElement($company);
    }

    /**
     * Get companies
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCompanies()
    {
        return $this->companies;
    }


    /**
     * Set user
     *
     * @param \QSCORBundle\Entity\User $user
     *
     * @return Project
     */
    public function setUser(\QSCORBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \QSCORBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add facility
     *
     * @param \QSCORBundle\Entity\Type_Facility $facility
     *
     * @return Project
     */
    public function addFacility(\QSCORBundle\Entity\Type_Facility $facility)
    {
        $this->facilities[] = $facility;

        return $this;
    }

    /**
     * Remove facility
     *
     * @param \QSCORBundle\Entity\Type_Facility $facility
     */
    public function removeFacility(\QSCORBundle\Entity\Type_Facility $facility)
    {
        $this->facilities->removeElement($facility);
    }

    /**
     * Get facilities
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacilities()
    {
        return $this->facilities;
    }

    /**
     * Set datecreation
     *
     * @param string $datecreation
     *
     * @return Project
     */
    public function setDatecreation($datecreation)
    {
        $this->datecreation = $datecreation;

        return $this;
    }

    /**
     * Get datecreation
     *
     * @return string
     */
    public function getDatecreation()
    {
        return $this->datecreation;
    }

    /**
     * Add flux
     *
     * @param \QSCORBundle\Entity\Flux $flux
     *
     * @return Project
     */
    public function addFlux(\QSCORBundle\Entity\Flux $flux)
    {
        $this->flux[] = $flux;

        return $this;
    }

    /**
     * Remove flux
     *
     * @param \QSCORBundle\Entity\Flux $flux
     */
    public function removeFlux(\QSCORBundle\Entity\Flux $flux)
    {
        $this->flux->removeElement($flux);
    }

    /**
     * Get flux
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFlux()
    {
        return $this->flux;
    }

    /**
     * Add performanceType
     *
     * @param \QSCORBundle\Entity\Performance_Type $performanceType
     *
     * @return Project
     */
    public function addPerformanceType(\QSCORBundle\Entity\Performance_Type $performanceType)
    {
        $this->performance_type[] = $performanceType;

        return $this;
    }

    /**
     * Remove performanceType
     *
     * @param \QSCORBundle\Entity\Performance_Type $performanceType
     */
    public function removePerformanceType(\QSCORBundle\Entity\Performance_Type $performanceType)
    {
        $this->performance_type->removeElement($performanceType);
    }

    /**
     * Get performanceType
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPerformanceType()
    {
        return $this->performance_type;
    }
}
