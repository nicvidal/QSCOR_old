<?php

namespace QSCORBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Performance
 *
 * @ORM\Table(name="performance")
 * @ORM\Entity(repositoryClass="QSCORBundle\Repository\PerformanceRepository")
 */
class Performance
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
     * @var int
     *
     * @ORM\Column(name="performancevalue", type="float")
     */
    private $performancevalue;

    /**
     * @var int
     *
     * @ORM\Column(name="performancesecond", type="float", nullable = true)
     */
    private $performancesecond;

    /**
     * @var int
     *
     * @ORM\Column(name="performancethreed", type="float", nullable = true)
     */
    private $performancethreed;

    /**
     * @var int
     *
     * @ORM\Column(name="performancefour", type="float", nullable = true)
     */
    private $performancefour;

    /**
     * @var int
     *
     * @ORM\Column(name="performancefive", type="float", nullable = true)
     */
    private $performancefive;

    /**
     * @var int
     *
     * @ORM\Column(name="performancesix", type="float", nullable = true)
     */
    private $performancesix;

    /**
     * @var int
     *
     * @ORM\Column(name="performanceseven", type="float", nullable = true)
     */
    private $performanceseven;

    /**
     * @var int
     *
     * @ORM\Column(name="performanceeight", type="float", nullable = true)
     */
    private $performanceeight;

    /**
     * @ORM\ManyToOne(targetEntity="Site", inversedBy="perfomance")
     * @ORM\JoinColumn(name="site_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $site;

    /**
     * @ORM\ManyToOne(targetEntity="Performance_Type", inversedBy="performance")
     * @ORM\JoinColumn(name="Performance_Type_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $performance_type;



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
     * Set performancevalue
     *
     * @param integer $performancevalue
     *
     * @return Performance
     */
    public function setPerformancevalue($performancevalue)
    {
        $this->performancevalue = $performancevalue;

        return $this;
    }

    /**
     * Get performancevalue
     *
     * @return int
     */
    public function getPerformancevalue()
    {
        return $this->performancevalue;
    }

    /**
     * Set site
     *
     * @param \QSCORBundle\Entity\Site $site
     *
     * @return Performance
     */
    public function setSite(\QSCORBundle\Entity\Site $site = null)
    {
        $this->site = $site;

        return $this;
    }

    /**
     * Get site
     *
     * @return \QSCORBundle\Entity\Site
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * Set performanceType
     *
     * @param \QSCORBundle\Entity\Performance_Type $performanceType
     *
     * @return Performance
     */
    public function setPerformanceType(\QSCORBundle\Entity\Performance_Type $performanceType = null)
    {
        $this->performance_type = $performanceType;

        return $this;
    }

    /**
     * Get performanceType
     *
     * @return \QSCORBundle\Entity\Performance_Type
     */
    public function getPerformanceType()
    {
        return $this->performance_type;
    }

    /**
     * Set performancesecond
     *
     * @param float $performancesecond
     *
     * @return Performance
     */
    public function setPerformancesecond($performancesecond)
    {
        $this->performancesecond = $performancesecond;

        return $this;
    }

    /**
     * Get performancesecond
     *
     * @return float
     */
    public function getPerformancesecond()
    {
        return $this->performancesecond;
    }

    /**
     * Set performancethreed
     *
     * @param float $performancethreed
     *
     * @return Performance
     */
    public function setPerformancethreed($performancethreed)
    {
        $this->performancethreed = $performancethreed;

        return $this;
    }

    /**
     * Get performancethreed
     *
     * @return float
     */
    public function getPerformancethreed()
    {
        return $this->performancethreed;
    }

    /**
     * Set performancefour
     *
     * @param float $performancefour
     *
     * @return Performance
     */
    public function setPerformancefour($performancefour)
    {
        $this->performancefour = $performancefour;

        return $this;
    }

    /**
     * Get performancefour
     *
     * @return float
     */
    public function getPerformancefour()
    {
        return $this->performancefour;
    }

    /**
     * Set performancefive
     *
     * @param float $performancefive
     *
     * @return Performance
     */
    public function setPerformancefive($performancefive)
    {
        $this->performancefive = $performancefive;

        return $this;
    }

    /**
     * Get performancefive
     *
     * @return float
     */
    public function getPerformancefive()
    {
        return $this->performancefive;
    }

    /**
     * Set performancesix
     *
     * @param float $performancesix
     *
     * @return Performance
     */
    public function setPerformancesix($performancesix)
    {
        $this->performancesix = $performancesix;

        return $this;
    }

    /**
     * Get performancesix
     *
     * @return float
     */
    public function getPerformancesix()
    {
        return $this->performancesix;
    }

    /**
     * Set performanceseven
     *
     * @param float $performanceseven
     *
     * @return Performance
     */
    public function setPerformanceseven($performanceseven)
    {
        $this->performanceseven = $performanceseven;

        return $this;
    }

    /**
     * Get performanceseven
     *
     * @return float
     */
    public function getPerformanceseven()
    {
        return $this->performanceseven;
    }

    /**
     * Set performanceeight
     *
     * @param float $performanceeight
     *
     * @return Performance
     */
    public function setPerformanceeight($performanceeight)
    {
        $this->performanceeight = $performanceeight;

        return $this;
    }

    /**
     * Get performanceeight
     *
     * @return float
     */
    public function getPerformanceeight()
    {
        return $this->performanceeight;
    }
}
