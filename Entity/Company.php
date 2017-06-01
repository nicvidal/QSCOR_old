<?php

namespace QSCORBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Company
 *
 * @ORM\Table(name="company")
 * @ORM\Entity(repositoryClass="QSCORBundle\Repository\CompanyRepository")
 * @UniqueEntity("name")
 */
class Company
{
    const SUPPLIER = "Supplier";
    const COMPANY = "MyCompany";
    const CUSTOMR = "Customer";
    const SUPPLIEROFSUPPLIER = "SupplierOfSupplier";
    const CUSTOMEROFCUSTORMER = "CustomerOfCustomer";
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
     * @ORM\Column(name="name", type="string", length=255,  unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="Country", type="string", length=255, nullable=true)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255)
     */
    private $city;


    /**
     * @var string
     *
     * @ORM\Column(name="category", type="string", length=255, nullable=true)
     *
     */

    private $category;

    /**
     * @ORM\OneToMany(targetEntity="Site", mappedBy="company")
     */
    private $sites;

    /**
     * @ORM\OneToMany(targetEntity="Flow_Site", mappedBy="companyorigin")
     */
    private $companyoriginflow;

    /**
     * @ORM\OneToMany(targetEntity="Flow_Site", mappedBy="companydestination")
     */
    private $companydestinationflow;

    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="companies")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $project;

    public  function __construct()
    {
        $this->sites = new ArrayCollection();
        $this->companyoriginflow = new ArrayCollection();
        $this->companydestinationflow = new ArrayCollection();
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
     * @return Company
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
     * Set description
     *
     * @param string $description
     *
     * @return Company
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return Company
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Company
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set category
     *
     * @param string $category
     *
     * @return Company
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }


    /**
     * Add site
     *
     * @param \QSCORBundle\Entity\Site $site
     *
     * @return Company
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

    /**
     * Set project
     *
     * @param \QSCORBundle\Entity\Project $project
     *
     * @return Company
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

    /* THis For My Select Option in Select 2 :)*/

    public function __toString()
    {
        // TODO: Implement __toString() method.

        return $this->getName();
    }



    /**
     * Add companyoriginflow
     *
     * @param \QSCORBundle\Entity\Flow_Site $companyoriginflow
     *
     * @return Company
     */
    public function addCompanyoriginflow(\QSCORBundle\Entity\Flow_Site $companyoriginflow)
    {
        $this->companyoriginflow[] = $companyoriginflow;

        return $this;
    }

    /**
     * Remove companyoriginflow
     *
     * @param \QSCORBundle\Entity\Flow_Site $companyoriginflow
     */
    public function removeCompanyoriginflow(\QSCORBundle\Entity\Flow_Site $companyoriginflow)
    {
        $this->companyoriginflow->removeElement($companyoriginflow);
    }

    /**
     * Get companyoriginflow
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCompanyoriginflow()
    {
        return $this->companyoriginflow;
    }

    /**
     * Add companydestinationflow
     *
     * @param \QSCORBundle\Entity\Flow_Site $companydestinationflow
     *
     * @return Company
     */
    public function addCompanydestinationflow(\QSCORBundle\Entity\Flow_Site $companydestinationflow)
    {
        $this->companydestinationflow[] = $companydestinationflow;

        return $this;
    }

    /**
     * Remove companydestinationflow
     *
     * @param \QSCORBundle\Entity\Flow_Site $companydestinationflow
     */
    public function removeCompanydestinationflow(\QSCORBundle\Entity\Flow_Site $companydestinationflow)
    {
        $this->companydestinationflow->removeElement($companydestinationflow);
    }

    /**
     * Get companydestinationflow
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCompanydestinationflow()
    {
        return $this->companydestinationflow;
    }
}
