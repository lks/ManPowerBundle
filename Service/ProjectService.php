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

	public function listUnassignProjects()
	{
		return $this->projectDao->listUnassignProjects();
	}

	/**
     * Get the Project with the given Id
     * 
     * @param projectId Project Id given
     *
     * @return Project object
     */
    public function getById($projectId)
    {
    	$project = $this->projectDao->getById($projectId);
    	if($project == null)
    	{
    		throw new NotFoundHttpException('Project not found with id '.$projectId);
    	}
    	return $project;
    }

    /**
     * List all 
     *
     * @return ArrayCollection
     */
    public function listProjects()
    {
    	return $this->projectDao->listProjects();
    }

    public function save($project)
    {
    	if($project != null)
    	{
    		$project = $this->assignMemberToProject($project);
    		$project = $this->projectDao->save($project);
    	}
    	return $project;
    }

	/**
	 * Assign the given project to the given member from the begin date and it will compute the endDate in function
	 * of the duration
	 * @param project Given project object
	 * @param beginDate Begin date of the project
	 *
	 * @return Project Object saved
	 */
	public function assignMemberToProject($project)
	{
		$member = $project->getMember();
		if($member != null)
		{
			 $project->setBeginDate($this->memberService->getAvailabilityDateMember($member, $project->getId()));

	        //add the estimation days to the endDate
	        $project->setEndDate($this->dateUtility->getEndDate($project->getBeginDate(), $project->getEstimation(), false));
		}
        return $project;
	}
}