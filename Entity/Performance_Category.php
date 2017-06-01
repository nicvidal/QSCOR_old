<?php

namespace QSCORBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Performance_Category
 *
 * @ORM\Table(name="performance_category")
 * @ORM\Entity(repositoryClass="QSCORBundle\Repository\Performance_CategoryRepository")
 */
class Performance_Category
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
     * @ORM\Column(name="libelle_category", type="string", length=255)
     */
    private $libelleCategory;


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
     * Set libelleCategory
     *
     * @param string $libelleCategory
     *
     * @return Performance_Category
     */
    public function setLibelleCategory($libelleCategory)
    {
        $this->libelleCategory = $libelleCategory;

        return $this;
    }

    /**
     * Get libelleCategory
     *
     * @return string
     */
    public function getLibelleCategory()
    {
        return $this->libelleCategory;
    }
}
