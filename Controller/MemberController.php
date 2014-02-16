<?php

namespace Lks\ManPowerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Lks\CapacityBundle\Entity\Member;
use Lks\CapacityBundle\Entity\Project;

class MemberController extends Controller
{

    public function allAction()
    {
        $repository = $this->getDoctrine() 
            ->getRepository('LksManPowerBundle:Member');

        $members = $repository->findAll();

        //get the calendar
        $period = new \DatePeriod(new \DateTime("NOW"), new \DateInterval("P1D"), 30);

        //get member calendar
        $membersCalendar = array();
        foreach ($members as $member)
        {
            $tmpArray = array();
            foreach ($period as $dt)
            {
                $tmpArray[$dt->format('d/m/Y')] = $this->projectAtGivenDate($member, $dt);
            }
            $membersCalendar[$member->getFirstname()] = $tmpArray;
        }
        
        return $this->render('LksManPowerBundle:Default:index.html.twig',
        	array('members' => $members,
                'period' => $period,
                'membersCalendar' => $membersCalendar)
        );
    }

    public function createAction(Request $request)
    {
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
    		$em = $this->getDoctrine()->getManager();
		    $em->persist($member);
		    $em->flush();

		    //TODO : Define a route
		    return $this->redirect($this->generateUrl('lks_man_power_member_all'));
    	}

        return $this->render('LksManPowerBundle:Default:create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function editAction(Request $request, $id)
    {
    	$repository = $this->getDoctrine() 
            ->getRepository('LksManPowerBundle:Member');

        $member = $repository->find($id);

        if ($member == null) {
            throw new NotFoundHttpException('Member not found');
        }

        $form = $this->createFormBuilder($member)
    		->add('firstname', 'text')
    		->add('lastname', 'text')
    		->add('save', 'submit')
    		->getForm();

    	//mamage the response of the form
    	$form->handleRequest($request);

    	if($form->isValid())
    	{
    		$em = $this->getDoctrine()->getManager();
		    $em->persist($member);
		    $em->flush();

		    return $this->redirect($this->generateUrl('lks_man_power_member_all'));
    	}

        return $this->render('LksManPowerBundle:Default:create.html.twig', array(
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
