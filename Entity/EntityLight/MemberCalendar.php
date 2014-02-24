<?php
namespace Lks\ManPowerBundle\Entity\EntityLight;

class MemberCalendar
{
	protected $member;

	protected $periods;

    public function getMember()
    {
        return $this->member;
    }

    public function setMember($member)
    {
        $this->member = $member;
    }

    public function getPeriods()
    {
        return $this->periods;
    }

    public function setPeriods($periods)
    {
        $this->periods = $periods;
    }

    
    /**
     * Constructor
     */
    public function __construct($member, $periods)
    {
        $this->member = $member;
        $this->periods = $periods;
    }
}