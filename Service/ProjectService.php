<?php

namespace Lks\ManPowerBundle\Service;

class ProjectService
{
	protected $memberService;
	protected $projectDao;
	protected $dateUtility;
	protected $logger;

	public function __construct($memberService, $projectDao, $dateUtility, $logger)
    {
    	$this->memberService = $memberService;
        $this->projectDao = $projectDao;
        $this->dateUtility = $dateUtility;
        $this->logger = $logger;
	}

	public function listUnassignProject()
	{
		return $this->projectDao->listUnassignProject();
	}

	/**
	 * Assign the given project to the given member from the begin date and it will compute the endDate in function
	 * of the duration
	 * @param project Given project object
	 * @param member Givent member object
	 * @param beginDate Begin date of the project
	 * @param duration Duration of the assignation in days
	 */
	public function assignMemberToProject($project, $member, $beginDate, $duration)
	{
		$project->setMember($member);
        $project->setBeginDate($this->memberService->getAvailabilityDateMember($member, $project->getId()));

        //add the estimation days to the endDate
        $project->setEndDate($$this->dateUtility->getEndDate($project->getBeginDate(), $duration, $false));
        return $this->projectDao->save($project);
	}
}