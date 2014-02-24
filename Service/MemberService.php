<?php

namespace Lks\ManPowerBundle\Service;

class MemberService 
{
	protected $memberDao;

	public function __construct($memberDao)
    {
        $this->memberDao = $memberDao;
	}

	/**
     * From a member object, the aim of this function is to determine the availability date
     * of a Member in function of the different projects.
     */
    public function getAvailabilityDateMember($member, $projectId)
    {
        $availabilityDate = new \DateTime("NOW");
        foreach($member->getProjects() as $project)
        {
            if(($project->getId() != $projectId) && ($project->getEndDate() > $availabilityDate))
            {
                $availabilityDate = $project->getEndDate();
            }
        }
        return $availabilityDate->add(new \DateInterval('P01D'));
    }
}