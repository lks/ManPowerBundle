<?php

namespace Lks\ManPowerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Lks\CapacityBundle\Entity\Member;
use Lks\CapacityBundle\Entity\Project;

class ProjectController extends Controller
{

    public function allAction()
    {
        $repository = $this->getDoctrine() 
            ->getRepository('LksManPowerBundle:Project');

        $projects = $repository->findAll();
        return $this->render('LksManPowerBundle:Project:index.html.twig',
        	array('projects' => $projects)
        );
    }

    public function createAction(Request $request)
    {
    	$project = new Project();

    	$form = $this->createFormBuilder($project)
    		->add('name', 'text')
    		->add('estimation', 'text')
    		->add('member', 'entity', array(
                     'class' => 'LksManPowerBundle:Member',
                     'property' => 'firstname',))
    		->add('save', 'submit')
    		->getForm();

    	//mamage the response of the form
    	$form->handleRequest($request);

    	if($form->isValid())
    	{
    		$em = $this->getDoctrine()->getManager();
		    $em->persist($member);
		    $em->flush();

		    //TODO : Define a route
		    return $this->redirect($this->generateUrl('lks_man_power_project_all'));
    	}

        return $this->render('LksManPowerBundle:Project:create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function editAction(Request $request, $id)
    {
    	$repository = $this->getDoctrine() 
            ->getRepository('LksManPowerBundle:Project');

        $project = $repository->find($id);

        if ($project == null) {
            throw new NotFoundHttpException('Project not found');
        }

        $form = $this->createFormBuilder($project)
    		->add('name', 'text')
    		->add('estimation', 'text')
    		->add('member', 'entity', array(
                     'class' => 'LksManPowerBundle:Member',
                     'property' => 'firstname',))
    		->add('save', 'submit')
    		->getForm();

    	//mamage the response of the form
    	$form->handleRequest($request);

    	if($form->isValid())
    	{
            //define the beginDate and the endDate
            //check the availibilty date of the member and define it as begin date
            $member = $project->getMember();
            if($member->getProjects() == null)
            {
                $project->setBeginDate(date_format(new \DateTime("now"), "Y-m-d H:i:s"));

            } else {
                $projectBeginDate = new \DateTime("now");
                foreach($member->getProjects() as $project)
                {
                    //TODO
                    if($project->getEndDate() != null)
                    {
                        $projectBeginDate = new $project->getEndDate();
                    }
                    
                } 
                $projectBeginDate->add(new \DateInterval('P1D'));
                $project->setBeginDate($projectBeginDate);
            }
            
            //add the estimation days to the endDate
            $projectEndDate = $project->getBeginDate();
            $projectEndDate->add(new \DateInterval('P'.$project->getEstimation().'D'));
            $project->setEndDate(date_format($projectEndDate, "Y-m-d H:i:s"));

    		
            $em = $this->getDoctrine()->getManager();
		    $em->persist($project);
		    $em->flush();

		    //TODO : Define a route
		    return $this->redirect($this->generateUrl('lks_man_power_project_all'));
    	}

        return $this->render('LksManPowerBundle:Project:create.html.twig', array(
            'form' => $form->createView(),
        ));

    }
}
