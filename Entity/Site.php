<?php

namespace QSCORBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Site
 *
 * @ORM\Table(name="site")
 * @ORM\Entity(repositoryClass="QSCORBundle\Repository\SiteRepository")
 * @UniqueEntity("name")
 */
class Site
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
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="coordonne", type="string", length=255)
     */
    private $coordonne;

    /**
     * @ORM\ManyToOne(targetEntity="Company", inversedBy="sites")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $company;

    /**
     * @ORM\ManyToOne(targetEntity="Type_Facility", inversedBy="sites")
     * @ORM\JoinColumn(name="Type_Facility_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $facility;

    /**
     * @ORM\OneToMany(targetEntity="Contact_Personne", mappedBy="site")
     */
    private $contactpersonnes;

    /**
     * @ORM\OneToMany(targetEntity="Level", mappedBy="site")
     */
    private $level;

    /**
     * @ORM\OneToMany(targetEntity="Performance", mappedBy="site")
     */
    private $performance;


    /**
    * @ORM\OneToMany(targetEntity="Flow_Site", mappedBy="origin")
    */
    private $siteorigin;
    /**
    * @ORM\OneToMany(targetEntity="Flow_Site", mappedBy="destination")
    */
    private $sitedestination;

    public function __construct()
    {
        $this->contactpersonnes = new ArrayCollection();
        $this->siteorigin = new ArrayCollection();
        $this->sitedestination = new ArrayCollection();
		//$this->performance= new ArrayCollection();
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
     * @return Site
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
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Site
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set coordonne
     *
     * @param string $coordonne
     *
     * @return Site
     */
    public function setCoordonne($coordonne)
    {
        $this->coordonne = $coordonne;

        return $this;
    }

    /**
     * Get coordonne
     *
     * @return string
     */
    public function getCoordonne()
    {
        return $this->coordonne;
    }

    /**
     * Set company
     *
     * @param \QSCORBundle\Entity\Company $company
     *
     * @return Site
     */
    public function setCompany(\QSCORBundle\Entity\Company $company = null)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return \QSCORBundle\Entity\Company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set facility
     *
     * @param \QSCORBundle\Entity\Type_Facility $facility
     *
     * @return Site
     */
    public function setFacility(\QSCORBundle\Entity\Type_Facility $facility = null)
    {
        $this->facility = $facility;

        return $this;
    }

    /**
     * Get facility
     *
     * @return \QSCORBundle\Entity\Type_Facility
     */
    public function getFacility()
    {
        return $this->facility;
    }

    /**
     * Add contactpersonne
     *
     * @param \QSCORBundle\Entity\Contact_Personne $contactpersonne
     *
     * @return Site
     */
    public function addContactpersonne(\QSCORBundle\Entity\Contact_Personne $contactpersonne)
    {
        $this->contactpersonnes[] = $contactpersonne;

        return $this;
    }

    /**
     * Remove contactpersonne
     *
     * @param \QSCORBundle\Entity\Contact_Personne $contactpersonne
     */
    public function removeContactpersonne(\QSCORBundle\Entity\Contact_Personne $contactpersonne)
    {
        $this->contactpersonnes->removeElement($contactpersonne);
    }

    /**
     * Get contactpersonnes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContactpersonnes()
    {
        return $this->contactpersonnes;
    }






    /**
     * Add siteorigin
     *
     * @param \QSCORBundle\Entity\Flow_Site $siteorigin
     *
     * @return Site
     */
    public function addSiteorigin(\QSCORBundle\Entity\Flow_Site $siteorigin)
    {
        $this->siteorigin[] = $siteorigin;

        return $this;
    }

    /**
     * Remove siteorigin
     *
     * @param \QSCORBundle\Entity\Flow_Site $siteorigin
     */
    public function removeSiteorigin(\QSCORBundle\Entity\Flow_Site $siteorigin)
    {
        $this->siteorigin->removeElement($siteorigin);
    }

    /**
     * Get siteorigin
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSiteorigin()
    {
        return $this->siteorigin;
    }

    /**
     * Add sitedestination
     *
     * @param \QSCORBundle\Entity\Flow_Site $sitedestination
     *
     * @return Site
     */
    public function addSitedestination(\QSCORBundle\Entity\Flow_Site $sitedestination)
    {
        $this->sitedestination[] = $sitedestination;

        return $this;
    }

    /**
     * Remove sitedestination
     *
     * @param \QSCORBundle\Entity\Flow_Site $sitedestination
     */
    public function removeSitedestination(\QSCORBundle\Entity\Flow_Site $sitedestination)
    {
        $this->sitedestination->removeElement($sitedestination);
    }

    /**
     * Get sitedestination
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSitedestination()
    {
        return $this->sitedestination;
    }

    public function __toString()
    {
        // TODO: Implement __toString() method.

        return $this->getName();
    }

    /**
     * Add level
     *
     * @param \QSCORBundle\Entity\Level $level
     *
     * @return Site
     */
    public function addLevel(\QSCORBundle\Entity\Level $level)
    {
        $this->level[] = $level;

        return $this;
    }

    /**
     * Remove level
     *
     * @param \QSCORBundle\Entity\Level $level
     */
    public function removeLevel(\QSCORBundle\Entity\Level $level)
    {
        $this->level->removeElement($level);
    }

    /**
     * Get level
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Add performance
     *
     * @param \QSCORBundle\Entity\Performance $performance
     *
     * @return Site
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
