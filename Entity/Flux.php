<?php

namespace QSCORBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Flux
 *
 * @ORM\Table(name="flux")
 * @ORM\Entity(repositoryClass="QSCORBundle\Repository\FluxRepository")
 * @UniqueEntity("libelleFlux")
 */
class Flux
{

    const fluxinformation = 'Flux Information';
    const fluxphysique = 'Flux Physique';
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
     * @ORM\Column(name="libelle_Flux", type="string", length=255, unique=true)
     */
    private $libelleFlux;

    /**
     * @var string
     *
     * @ORM\Column(name="color_Flux", type="string", length=255)
     */
    private $colorFlux;

    /**
     * @var int
     *
     * @ORM\Column(name="typeflux", type="string", length = 200)
     */
    private $typeflux;

    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="flux")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $project;

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
     * Set libelleFlux
     *
     * @param string $libelleFlux
     *
     * @return Flux
     */
    public function setLibelleFlux($libelleFlux)
    {
        $this->libelleFlux = $libelleFlux;

        return $this;
    }

    /**
     * Get libelleFlux
     *
     * @return string
     */
    public function getLibelleFlux()
    {
        return $this->libelleFlux;
    }

    /**
     * Set colorFlux
     *
     * @param string $colorFlux
     *
     * @return Flux
     */
    public function setColorFlux($colorFlux)
    {
        $this->colorFlux = $colorFlux;

        return $this;
    }

    /**
     * Get colorFlux
     *
     * @return string
     */
    public function getColorFlux()
    {
        return $this->colorFlux;
    }
    

    /**
     * Set typeflux
     *
     * @param string $typeflux
     *
     * @return Flux
     */
    public function setTypeflux($typeflux)
    {
        $this->typeflux = $typeflux;

        return $this;
    }

    /**
     * Get typeflux
     *
     * @return string
     */
    public function getTypeflux()
    {
        return $this->typeflux;
    }


    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->libelleFlux;
    }





    /**
     * Set project
     *
     * @param \QSCORBundle\Entity\Project $project
     *
     * @return Flux
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
