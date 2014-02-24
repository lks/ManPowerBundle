<?php

namespace Lks\ManPowerBundle\Service;

class ProjectService
{
	protected $memberService;
	protected $projectDao;
	protected $logger;

	public function __construct($memberService, $projectDao, $logger)
    {
    	$this->memberService = $memberService;
        $this->projectDao = $projectDao;
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
		$this->logger->info('Project: '.$project->getId().'; Member: '.$member->getFirstname().'; BeginDate: '.$beginDate->format('d/m/Y').'; Duration: '.$duration);
        $project->setMember($member);
        $project->setBeginDate($this->memberService->getAvailabilityDateMember($member, $project->getId()));

        //add the estimation days to the endDate
        $projectEndDate = clone $project->getBeginDate();
        $projectEndDate->add(new \DateInterval('P'.$duration.'D'));
        $project->setEndDate($projectEndDate);
        $project = $this->projectDao->save($project);

        return $project;
	}
}