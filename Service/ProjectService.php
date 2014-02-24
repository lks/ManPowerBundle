<?php

namespace Lks\ManPowerBundle\Service;

class ProjectService
{
	protected $projectDao;

	public function __construct($projectDao)
    {
        $this->projectDao = $projectDao;
	}

	public function listUnassignProject()
	{
		return $this->projectDao->listUnassignProject();
	}
}