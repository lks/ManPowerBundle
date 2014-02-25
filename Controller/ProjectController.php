<?php

namespace Lks\ManPowerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Lks\ManPowerBundle\Entity\Member;
use Lks\ManPowerBundle\Entity\Project;

class ProjectController extends Controller
{

    public function allAction()
    {
        $projectService = $this->get('projectService');

        $projects = $projectService->listProjects();

        return $this->render('LksManPowerBundle:Project:index.html.twig',
        	array('projects' => $projects)
        );
    }

    public function createAction(Request $request)
    {
        $projectService = $this->get('projectService');

    	$project = new Project();

    	$form = $this->createFormBuilder($project)
    		->add('name', 'text')
    		->add('estimation', 'text')
    		->add('member', 'entity', array(
                     'class' => 'LksManPowerBundle:Member',
                     'property' => 'firstname',
                     'empty_value' => 'Choisissez une option',
                     'required' => false))
    		->add('save', 'submit')
    		->getForm();

    	//mamage the response of the form
    	$form->handleRequest($request);

    	if($form->isValid())
    	{
            $project = $projectService->save($project);

		    //TODO : Define a route
		    return $this->redirect($this->generateUrl('lks_man_power_project_all'));
    	}

        return $this->render('LksManPowerBundle:Project:create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function editAction(Request $request, $id)
    {
        $projectService = $this->get('projectService');

        $project = $projectService->getById($id);

        $form = $this->createFormBuilder($project)
    		->add('name', 'text')
    		->add('estimation', 'text')
    		->add('member', 'entity', array(
                     'class' => 'LksManPowerBundle:Member',
                     'property' => 'firstname',
                     'empty_value' => 'Choisissez une option',
                     'required' => false))
    		->add('save', 'submit')
    		->getForm();

    	//mamage the response of the form
    	$form->handleRequest($request);

    	if($form->isValid())
    	{
            $project = $projectService->save($project);

		    //TODO : Define a route
		    return $this->redirect($this->generateUrl('lks_man_power_project_all'));
    	}

        return $this->render('LksManPowerBundle:Project:create.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    public function assignAction(Request $request, $memberId, $date) {

        $memberService = $this->get('memberService');
        $projectService = $this->get('projectService');

        $logger = $this->get('logger');

        $repository = $this->getDoctrine() 
            ->getRepository('LksManPowerBundle:Member');

        $member = $memberService->getById($memberId);

        //get Projects without assignation

        $defaultData = array('message' => 'Type your message here');
         $form = $this->createFormBuilder($defaultData)
            ->add('project', 'entity', array(
                'class' => 'LksManPowerBundle:Project',
                'choices' => $projectService->listUnassignProjects(),
                'empty_value' => 'Choisissez une option',
                'property' => 'name'))
            ->add('save', 'submit')
            ->getForm();

        //mamage the response of the form
        $form->handleRequest($request);

        if($form->isValid())
        {
            $data = $form->getData();
            $project = $data['project'];
            $project->setBeginDate(new \DateTime($date));
            $project->setMember($member);
            $project = $projectService->save($project);

            //TODO : Define a route
            return $this->redirect($this->generateUrl('lks_man_power_member_all'));
        }

        return $this->render('LksManPowerBundle:Member:assign.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * From a member object, the aim of this function is to determine the availability date
     * of a Member in function of the different projects.
     */
    protected function getAvailabilityDateMember($member, $projectId)
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
}
