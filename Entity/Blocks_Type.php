<?php

namespace QSCORBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Blocks_Type
 *
 * @ORM\Table(name="blocks_type")
 * @ORM\Entity(repositoryClass="QSCORBundle\Repository\Blocks_TypeRepository")
 */
class Blocks_Type
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
     * @ORM\Column(name="libelle_blocks_type", type="string", length=255)
     */
    private $libelleBlocksType;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle_abr", type="string", length=255)
     */
    private $libelleAbr;
    
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
//    * @ORM\ManyToMany(targetEntity="Level_Type", inversedBy="block_type")
//    * @ORM\JoinTable(name="level_type_level")
//    */
//    private $level_type;

    /**
     * @ORM\ManyToOne(targetEntity="Level_Type", inversedBy="block_type")
     * @ORM\JoinColumn(name="level_type_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $level_type;


    /**
     * @ORM\OneToMany(targetEntity="Process_blocks", mappedBy="block_type")
     */
    private $process_blocks;


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
     * Set libelleBlocksType
     *
     * @param string $libelleBlocksType
     *
     * @return Blocks_Type
     */
    public function setLibelleBlocksType($libelleBlocksType)
    {
        $this->libelleBlocksType = $libelleBlocksType;

        return $this;
    }

    /**
     * Get libelleBlocksType
     *
     * @return string
     */
    public function getLibelleBlocksType()
    {
        return $this->libelleBlocksType;
    }

    /**
     * Set libelleAbr
     *
     * @param string $libelleAbr
     *
     * @return Blocks_Type
     */
    public function setLibelleAbr($libelleAbr)
    {
        $this->libelleAbr = $libelleAbr;

        return $this;
    }

    /**
     * Get libelleAbr
     *
     * @return string
     */
    public function getLibelleAbr()
    {
        return $this->libelleAbr;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->process_blocks = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add processBlock
     *
     * @param \QSCORBundle\Entity\Process_blocks $processBlock
     *
     * @return Blocks_Type
     */
    public function addProcessBlock(\QSCORBundle\Entity\Process_blocks $processBlock)
    {
        $this->process_blocks[] = $processBlock;

        return $this;
    }

    /**
     * Remove processBlock
     *
     * @param \QSCORBundle\Entity\Process_blocks $processBlock
     */
    public function removeProcessBlock(\QSCORBundle\Entity\Process_blocks $processBlock)
    {
        $this->process_blocks->removeElement($processBlock);
    }

    /**
     * Get processBlocks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProcessBlocks()
    {
        return $this->process_blocks;
    }

    /**
     * Set levelType
     *
     * @param \QSCORBundle\Entity\Level_Type $levelType
     *
     * @return Blocks_Type
     */
    public function setLevelType(\QSCORBundle\Entity\Level_Type $levelType = null)
    {
        $this->level_type = $levelType;

        return $this;
    }

    /**
     * Get levelType
     *
     * @return \QSCORBundle\Entity\Level_Type
     */
    public function getLevelType()
    {
        return $this->level_type;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Blocks_Type
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
     * @return Blocks_Type
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
}
