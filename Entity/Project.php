<?php
namespace Lks\ManPowerBundle\Entity;

use Lks\ManPowerBundle\Entity\Member;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="project")
 */
class Project
{
	/**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
	protected $id;

	/**
     * @ORM\Column(type="string", length=100)
     */
	protected $name;


    /**
     * @ORM\Column(type="decimal")
     */
    protected $estimation;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $beginDate;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $endDate;

	/**
     * @ORM\ManyToOne(targetEntity="Lks\ManPowerBundle\Entity\Member", inversedBy="projects")
     * @ORM\JoinColumn(name="member_id", referencedColumnName="id")
     */
	protected $member;

    function __construct()
    {
    }
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Project
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set member
     *
     * @param \Lks\ManPowerBundle\Entity\Member $member
     * @return Project
     */
    public function setMember(\Lks\ManPowerBundle\Entity\Member $member = null)
    {
        $this->member = $member;
    
        return $this;
    }

    /**
     * Get member
     *
     * @return \Lks\ManPowerBundle\Entity\Member 
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * Set estimation
     *
     * @param float $estimation
     * @return Project
     */
    public function setEstimation($estimation)
    {
        $this->estimation = $estimation;
    
        return $this;
    }

    /**
     * Get estimation
     *
     * @return float 
     */
    public function getEstimation()
    {
        return $this->estimation;
    }

    /**
     * Set begin_date
     *
     * @param \DateTime $beginDate
     * @return Project
     */
    public function setBeginDate($beginDate)
    {
        $this->beginDate = $beginDate;
    
        return $this;
    }

    /**
     * Get begin_date
     *
     * @return \DateTime 
     */
    public function getBeginDate()
    {
        return $this->beginDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     * @return Project
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
    
        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime 
     */
    public function getEndDate()
    {
        return $this->endDate;
    }
}