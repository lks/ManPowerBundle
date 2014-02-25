<?php

namespace Lks\ManPowerBundle\Service;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    /**
     * Save the given member
     * 
     * @param member Member object given
     *
     * @return Member object saved
     */
    public function getById($memberId)
    {
    	$member = $this->memberDao->getById($memberId);
    	if($member == null)
    	{
    		throw new NotFoundHttpException('Member not found with id '.$memberId);
    	}
    	return $member;
    }

    /**
     * Save the given member
     * 
     * @param member Member object given
     *
     * @return Member object saved
     */
    public function save($member)
    {
    	if($member != null)
    	{
    		$member = $this->memberDao->save($member);
    	}
    	return $member;
    }

    /**
     * List all 
     * 
     * @param member Member object given
     *
     * @return ArrayCollection
     */
    public function listMembers()
    {
    	return $this->memberDao->listMembers();
    }


    /**
     * List the availibilities of a member.
     * 
     * @param member Member object given
     *
     * @return ArrayCollection of availibility periods
     */
    public function listAvailabilities($member)
    {
    	return null;
    }
}