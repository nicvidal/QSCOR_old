<?php

namespace QSCORBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CountryMaps
 *
 * @ORM\Table(name="country_maps")
 * @ORM\Entity(repositoryClass="QSCORBundle\Repository\CountryMapsRepository")
 */
class CountryMaps
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
     * @ORM\Column(name="Country_Name", type="string", length=255)
     */
    private $countryName;

    /**
     * @var string
     *
     * @ORM\Column(name="Capital_Name", type="string", length=255)
     */
    private $capitalName;

    /**
     * @var string
     *
     * @ORM\Column(name="Capital_Latitude", type="string", length=255)
     */
    private $capitalLatitude;

    /**
     * @var string
     *
     * @ORM\Column(name="Capital_Longitude", type="string", length=255)
     */
    private $capitalLongitude;

    /**
     * @var string
     *
     * @ORM\Column(name="Country_Code", type="string", length=255)
     */
    private $countryCode;

    /**
     * @var string
     *
     * @ORM\Column(name="Continent_Name", type="string", length=255)
     */
    private $continentName;


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
     * Set countryName
     *
     * @param string $countryName
     *
     * @return CountryMaps
     */
    public function setCountryName($countryName)
    {
        $this->countryName = $countryName;

        return $this;
    }

    /**
     * Get countryName
     *
     * @return string
     */
    public function getCountryName()
    {
        return $this->countryName;
    }

    /**
     * Set capitalName
     *
     * @param string $capitalName
     *
     * @return CountryMaps
     */
    public function setCapitalName($capitalName)
    {
        $this->capitalName = $capitalName;

        return $this;
    }

    /**
     * Get capitalName
     *
     * @return string
     */
    public function getCapitalName()
    {
        return $this->capitalName;
    }

    /**
     * Set capitalLatitude
     *
     * @param string $capitalLatitude
     *
     * @return CountryMaps
     */
    public function setCapitalLatitude($capitalLatitude)
    {
        $this->capitalLatitude = $capitalLatitude;

        return $this;
    }

    /**
     * Get capitalLatitude
     *
     * @return string
     */
    public function getCapitalLatitude()
    {
        return $this->capitalLatitude;
    }

    /**
     * Set capitalLongitude
     *
     * @param string $capitalLongitude
     *
     * @return CountryMaps
     */
    public function setCapitalLongitude($capitalLongitude)
    {
        $this->capitalLongitude = $capitalLongitude;

        return $this;
    }

    /**
     * Get capitalLongitude
     *
     * @return string
     */
    public function getCapitalLongitude()
    {
        return $this->capitalLongitude;
    }

    /**
     * Set countryCode
     *
     * @param string $countryCode
     *
     * @return CountryMaps
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * Get countryCode
     *
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * Set continentName
     *
     * @param string $continentName
     *
     * @return CountryMaps
     */
    public function setContinentName($continentName)
    {
        $this->continentName = $continentName;

        return $this;
    }

    /**
     * Get continentName
     *
     * @return string
     */
    public function getContinentName()
    {
        return $this->continentName;
    }

    /* THis For My Select Option in Select 2 :)*/

    public function __toString()
    {
        // TODO: Implement __toString() method.

       return $this->getCountryName();
    }
}
