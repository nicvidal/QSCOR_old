<?php

namespace QSCORBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Flow_Site
 *
 * @ORM\Table(name="flow_site")
 * @ORM\Entity(repositoryClass="QSCORBundle\Repository\Flow_SiteRepository")
 */
class Flow_Site
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
     * @ORM\ManyToMany(targetEntity="Flux")
     * @ORM\JoinTable(name="flow_site_fluxphysique",
     *      joinColumns={@ORM\JoinColumn(name="fluxphysique_id", referencedColumnName="id", onDelete="CASCADE"),},
     *      inverseJoinColumns={@ORM\JoinColumn(name="flow_fluxphysique_id", referencedColumnName="id", onDelete="CASCADE")}
     *      )
     */
    private $flowfluxphysique;

    /**
     * @ORM\ManyToMany(targetEntity="Flux")
     * @ORM\JoinTable(name="flow_site_fluxinformation",
     *      joinColumns={@ORM\JoinColumn(name="fluxinformation_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="flow_fluxinformation_id", referencedColumnName="id", onDelete="CASCADE")}
     *      )
     */
    private $flowfluxinformation;

    /**
     * @ORM\ManyToOne(targetEntity="Site", inversedBy="siteorigin")
     * @ORM\JoinColumn(name="origin_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $origin;
    /**
     * @ORM\ManyToOne(targetEntity="Site", inversedBy="sitedestination")
     * @ORM\JoinColumn(name="destination_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $destination;

    /**
     * @ORM\ManyToOne(targetEntity="Company", inversedBy="companyoriginflow", cascade={"persist"})
     * @ORM\JoinColumn(name="companyorigin_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $companyorigin;


    /**
     * @ORM\ManyToOne(targetEntity="Company", inversedBy="companydestinationflow", cascade={"persist"})
     * @ORM\JoinColumn(name="companydestination_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $companydestination;




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
     * Set origin
     *
     * @param \QSCORBundle\Entity\Site $origin
     *
     * @return Flow_Site
     */
    public function setOrigin(\QSCORBundle\Entity\Site $origin = null)
    {
        $this->origin = $origin;

        return $this;
    }

    /**
     * Get origin
     *
     * @return \QSCORBundle\Entity\Site
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * Set destination
     *
     * @param \QSCORBundle\Entity\Site $destination
     *
     * @return Flow_Site
     */
    public function setDestination(\QSCORBundle\Entity\Site $destination = null)
    {
        $this->destination = $destination;

        return $this;
    }

    /**
     * Get destination
     *
     * @return \QSCORBundle\Entity\Site
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * Set companyorigin
     *
     * @param \QSCORBundle\Entity\Company $companyorigin
     *
     * @return Flow_Site
     */
    public function setCompanyorigin(\QSCORBundle\Entity\Company $companyorigin = null)
    {
        $this->companyorigin = $companyorigin;

        return $this;
    }

    /**
     * Get companyorigin
     *
     * @return \QSCORBundle\Entity\Company
     */
    public function getCompanyorigin()
    {
        return $this->companyorigin;
    }

    /**
     * Set companydestination
     *
     * @param \QSCORBundle\Entity\Company $companydestination
     *
     * @return Flow_Site
     */
    public function setCompanydestination(\QSCORBundle\Entity\Company $companydestination = null)
    {
        $this->companydestination = $companydestination;

        return $this;
    }

    /**
     * Get companydestination
     *
     * @return \QSCORBundle\Entity\Company
     */
    public function getCompanydestination()
    {
        return $this->companydestination;
    }

    /**
     * Set flowfluxphysique
     *
     * @param \QSCORBundle\Entity\Flux $flowfluxphysique
     *
     * @return Flow_Site
     */
    public function setFlowfluxphysique(\QSCORBundle\Entity\Flux $flowfluxphysique = null)
    {
        $this->flowfluxphysique = $flowfluxphysique;

        return $this;
    }

    /**
     * Get flowfluxphysique
     *
     * @return \QSCORBundle\Entity\Flux
     */
    public function getFlowfluxphysique()
    {
        return $this->flowfluxphysique;
    }

    /**
     * Set flowfluxinformation
     *
     * @param \QSCORBundle\Entity\Flux $flowfluxinformation
     *
     * @return Flow_Site
     */
    public function setFlowfluxinformation(\QSCORBundle\Entity\Flux $flowfluxinformation = null)
    {
        $this->flowfluxinformation = $flowfluxinformation;

        return $this;
    }

    /**
     * Get flowfluxinformation
     *
     * @return \QSCORBundle\Entity\Flux
     */
    public function getFlowfluxinformation()
    {
        return $this->flowfluxinformation;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->flowfluxphysique = new \Doctrine\Common\Collections\ArrayCollection();
        $this->flowfluxinformation = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add flowfluxphysique
     *
     * @param \QSCORBundle\Entity\Flux $flowfluxphysique
     *
     * @return Flow_Site
     */
    public function addFlowfluxphysique(\QSCORBundle\Entity\Flux $flowfluxphysique)
    {
        $this->flowfluxphysique[] = $flowfluxphysique;

        return $this;
    }

    /**
     * Remove flowfluxphysique
     *
     * @param \QSCORBundle\Entity\Flux $flowfluxphysique
     */
    public function removeFlowfluxphysique(\QSCORBundle\Entity\Flux $flowfluxphysique)
    {
        $this->flowfluxphysique->removeElement($flowfluxphysique);
    }

    /**
     * Add flowfluxinformation
     *
     * @param \QSCORBundle\Entity\Flux $flowfluxinformation
     *
     * @return Flow_Site
     */
    public function addFlowfluxinformation(\QSCORBundle\Entity\Flux $flowfluxinformation)
    {
        $this->flowfluxinformation[] = $flowfluxinformation;

        return $this;
    }

    /**
     * Remove flowfluxinformation
     *
     * @param \QSCORBundle\Entity\Flux $flowfluxinformation
     */
    public function removeFlowfluxinformation(\QSCORBundle\Entity\Flux $flowfluxinformation)
    {
        $this->flowfluxinformation->removeElement($flowfluxinformation);
    }
}
