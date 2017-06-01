<?php

namespace QSCORBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Process_blocks
 *
 * @ORM\Table(name="process_blocks")
 * @ORM\Entity(repositoryClass="QSCORBundle\Repository\Process_blocksRepository")
 */
class Process_blocks
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

    /**
     * @ORM\ManyToOne(targetEntity="Blocks_Type", inversedBy="process_blocks")
     * @ORM\JoinColumn(name="blocks_type_id", referencedColumnName="id", onDelete="CASCADE")
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
     * @return Process_blocks
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
     * Set libelleAbr
     *
     * @param string $libelleAbr
     *
     * @return Process_blocks
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
     * Set blockType
     *
     * @param \QSCORBundle\Entity\Blocks_Type $blockType
     *
     * @return Process_blocks
     */
    public function setBlockType(\QSCORBundle\Entity\Blocks_Type $blockType = null)
    {
        $this->block_type = $blockType;

        return $this;
    }

    /**
     * Get blockType
     *
     * @return \QSCORBundle\Entity\Blocks_Type
     */
    public function getBlockType()
    {
        return $this->block_type;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Process_blocks
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
     * @return Process_blocks
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
