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

	/**
     * List all unassigned project
	 *
	 * @return ArrayCollection contained the list of unassigned project
     */
    public function listUnassignProject()
    {
    	$query =  $this->repository->createQueryBuilder('p')
    		->where('p.member is null')
    		->getQuery();

    	return $query->getResult();
    }

    /**
     * Save an project item
     *
	 * @param project Project object to save
	 *
	 * @return Project Object saved
     */
    public function save($project)
    {
        $this->em->persist($project);
        $this->em->flush();
        return $project;
    }
}