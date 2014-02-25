<?php

namespace Lks\ManPowerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Lks\ManPowerBundle\Entity\Member;
use Lks\ManPowerBundle\Entity\Project;
use Lks\ManPowerBundle\Entity\EntityLight\MemberCalendar;

class MemberController extends Controller
{

    public function allAction()
    {
        $dateUtility = $this->get('dateUtility');
        $memberService = $this->get('memberService');
        
        $members = $memberService->listMembers();

        //get the calendar
        $period = $dateUtility->getPeriod(new \DateTime("NOW"), 30, false);

        //get member calendar
        $membersCalendar = array();
        foreach ($members as $member)
        {
            $tmpArray = array();
            foreach ($period as $dt)
            {
                $tmpArray[$dt->format('d-m-Y')] = $this->projectAtGivenDate($member, $dt);
            }
            $membersCalendar[$member->getFirstname()] = new memberCalendar($member, $tmpArray);
        }
        
        return $this->render('LksManPowerBundle:Member:index.html.twig',
        	array('members' => $members,
                'period' => $period,
                'membersCalendar' => $membersCalendar)
        );
    }

    public function createAction(Request $request)
    {
    	$memberService = $this->get('memberService');

    	$member = new Member();

    	$form = $this->createFormBuilder($member)
    		->add('firstname', 'text')
    		->add('lastname', 'text')
    		->add('save', 'submit')
    		->getForm();

    	//mamage the response of the form
    	$form->handleRequest($request);

    	if($form->isValid())
    	{
    		$memberService->save($member);

		    //TODO : Define a route
		    return $this->redirect($this->generateUrl('lks_man_power_member_all'));
    	}

        return $this->render('LksManPowerBundle:Member:create.html.twig', array(
            'form' => $form->createView(),
        ));
    }


    public function editAction(Request $request, $id)
    {

        $memberService = $this->get('memberService');

    	$member = $memberService->getById($id);

        $form = $this->createFormBuilder($member)
    		->add('firstname', 'text')
    		->add('lastname', 'text')
    		->add('save', 'submit')
    		->getForm();

    	//mamage the response of the form
    	$form->handleRequest($request);

    	if($form->isValid())
    	{
    		$memberService->save($member);

		    return $this->redirect($this->generateUrl('lks_man_power_member_all'));
    	}

        return $this->render('LksManPowerBundle:Member:create.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    /**
     * The aim is to know is the given member is or isn't available. 
     * This function returns the project name.
     */
    protected function projectAtGivenDate($member, $date)
    {
        foreach($member->getProjects() as $project) 
        {
            if($project->getBeginDate() <= $date && $project->getEndDate() >= $date)
            {
                return $project->getName();
            }
        }
        return null;
    }
}
