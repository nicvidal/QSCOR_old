<?php

namespace QSCORBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Level_Type
 *
 * @ORM\Table(name="level_type")
 * @ORM\Entity(repositoryClass="QSCORBundle\Repository\Level_TypeRepository")
 */
class Level_Type
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
     * @ORM\Column(name="libelle_Type", type="string", length=255)
     */
    private $libelleType;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    private $comment;

//    /**
//     * @ORM\ManyToMany(targetEntity="Level", inversedBy="levels_types")
//     * @ORM\JoinTable(name="level_type_level")
//     */
//    private $level;

    /**
     * @ORM\OneToMany(targetEntity="Blocks_Type", mappedBy="level_type")
     */
    private $block_type;
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
     * @return Level_Type
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

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Level_Type
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
     * Set comment
     *
     * @param string $comment
     *
     * @return Level_Type
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->block_type = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add blockType
     *
     * @param \QSCORBundle\Entity\Blocks_Type $blockType
     *
     * @return Level_Type
     */
    public function addBlockType(\QSCORBundle\Entity\Blocks_Type $blockType)
    {
        $this->block_type[] = $blockType;

        return $this;
    }

    /**
     * Remove blockType
     *
     * @param \QSCORBundle\Entity\Blocks_Type $blockType
     */
    public function removeBlockType(\QSCORBundle\Entity\Blocks_Type $blockType)
    {
        $this->block_type->removeElement($blockType);
    }

    /**
     * Get blockType
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBlockType()
    {
        return $this->block_type;
    }
}
