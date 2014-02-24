<?php
namespace Lks\ManPowerBundle\Dao;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;

class ProjectDao
{
	protected $em;
	protected $repository;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->repository = $this->em->getRepository('LksManPowerBundle:Project');
    }

    public function listUnassignProject()
    {
    	return $this->repository->findAll();
    }
}