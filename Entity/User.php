<?php
// src/AppBundle/Entity/User.php

namespace QSCORBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="qscor_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Project", mappedBy="user")
     */
    private $projects;

    public function __construct()
    {
        parent::__construct();
        // your own logic
        $this->products = new ArrayCollection();
    }

    /**
     * Add project
     *
     * @param \QSCORBundle\Entity\Project $project
     *
     * @return User
     */
    public function addProject(\QSCORBundle\Entity\Project $project)
    {
        $this->projects[] = $project;

        return $this;
    }

    /**
     * Remove project
     *
     * @param \QSCORBundle\Entity\Project $project
     */
    public function removeProject(\QSCORBundle\Entity\Project $project)
    {
        $this->projects->removeElement($project);
    }

    /**
     * Get projects
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProjects()
    {
        return $this->projects;
    }
}
