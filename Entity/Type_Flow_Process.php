<?php

namespace QSCORBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Type_Flow_Process
 *
 * @ORM\Table(name="type_flow_process")
 * @ORM\Entity(repositoryClass="QSCORBundle\Repository\Type_Flow_ProcessRepository")
 */
class Type_Flow_Process
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
     * @ORM\Column(name="libelle_type", type="string", length=255)
     */
    private $libelleType;


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
     * Set libelleType
     *
     * @param string $libelleType
     *
     * @return Type_Flow_Process
     */
    public function setLibelleType($libelleType)
    {
        $this->libelleType = $libelleType;

        return $this;
    }

    /**
     * Get libelleType
     *
     * @return string
     */
    public function getLibelleType()
    {
        return $this->libelleType;
    }
}
