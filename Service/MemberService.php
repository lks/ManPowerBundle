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
	 * Assign the given project to the given member from the begin date and it will compute the endDate in function
	 * of the duration
	 * @param project Given project object
	 * @param member Givent member object
	 * @param beginDate Begin date of the project
	 * @param duration Duration of the assignation in days
	 */
	public function addProjectToMember($project, $member, $beginDate, $duration)
	{
		array_push($member->getProjects(), $project);
	}
}