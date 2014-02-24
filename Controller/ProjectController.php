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
                     'property' => 'firstname',
                     'empty_value' => 'Choisissez une option',
                     'required' => false))
    		->add('save', 'submit')
    		->getForm();

    	//mamage the response of the form
    	$form->handleRequest($request);

    	if($form->isValid())
    	{
            $member = $project->getMember();
            if(empty($member))
            {
                $project->setBeginDate($this->getAvailabilityDateMember($member, $project->getId()));

                //add the estimation days to the endDate
                $projectEndDate = clone $project->getBeginDate();
                $projectEndDate->add(new \DateInterval('P'.$project->getEstimation().'D'));
                $project->setEndDate($projectEndDate);
            } 

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
                     'property' => 'firstname',
                     'empty_value' => 'Choisissez une option',
                     'required' => false))
    		->add('save', 'submit')
    		->getForm();

    	//mamage the response of the form
    	$form->handleRequest($request);

    	if($form->isValid())
    	{
            //define the beginDate and the endDate
            //check the availibilty date of the member and define it as begin date
            $member = $project->getMember();
            if(!empty($member))
            {
                $member = $project->getMember();
                $project->setBeginDate($this->getAvailabilityDateMember($member, $project->getId()));

                //add the estimation days to the endDate
                $projectEndDate = clone $project->getBeginDate();
                $projectEndDate->add(new \DateInterval('P'.$project->getEstimation().'D'));
                $project->setEndDate($projectEndDate);
            }  
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

    public function assignAction(Request $request, $memberId, $date) {
        $memberService = $this->get('memberService');
        $projectService = $this->get('projectService');
        $logger = $this->get('logger');

        $repository = $this->getDoctrine() 
            ->getRepository('LksManPowerBundle:Member');

        $member = $repository->find($memberId);

        if ($member == null) {
            throw new NotFoundHttpException('Member not found');
        }

        //get Projects without assignation

        $defaultData = array('message' => 'Type your message here');
         $form = $this->createFormBuilder($defaultData)
            ->add('project', 'entity', array(
                'class' => 'LksManPowerBundle:Project',
                'choices' => $projectService->listUnassignProject(),
                'empty_value' => 'Choisissez une option',
                'property' => 'name'))
            ->add('save', 'submit')
            ->getForm();

        //mamage the response of the form
        $form->handleRequest($request);

        if($form->isValid())
        {
            $data = $form->getData();
            $dateTime = new \DateTime($date);

            $project = $projectService->assignMemberToProject($data['project'], $member, $dateTime, 1);

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
