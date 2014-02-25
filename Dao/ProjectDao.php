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

    public function listProjects($params = null)
    {
        $projects = null;
        if($params == null)
        {
            $projects = $this->repository->findAll();
        } else {
            $projects = $this->repository->findBy($params);
        }

        return $projects;
    }

	/**
     * List all unassigned project
	 *
	 * @return ArrayCollection contained the list of unassigned project
     */
    public function listUnassignProjects()
    {
    	$query =  $this->repository->createQueryBuilder('p')
    		->where('p.member is null')
    		->getQuery();

    	return $query->getResult();
    }

    public function getById($id)
    {
        $project = $this->repository->find($id);
        return $project;
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