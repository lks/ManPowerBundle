<?php

namespace Lks\ManPowerBundle\Utility;

class DateUtility
{
	public function __construct()
	{

	}	

	/**
	 * Compute the period from the beginDate and the duration.
	 * 
	 * @param beginDate Start date for the period
	 * @param duration Number of day
	 * @param withWeekEnd True if we want the WeekEnd included, false else
	 *
	 * @return ArrayCollection
	 */
	public function getPeriod($beginDate, $duration, $withWeekEnd)
	{
		$period = new \DatePeriod($beginDate, new \DateInterval("P1D"), 30);
		if(!$withWeekEnd)
		{
			$tmp = array();
			foreach ($period as $dt)
			{
				if($dt->format('N') != 6 && $dt->format('N') != 7)
				{
					array_push($tmp, $dt);
				}
			}
			$period = $tmp;
		}
		return $period;
	}

	public function computeEndDate($beginDate, $duration, $withWeekEnd)
	{
		$projectEndDate->add(new \DateInterval('P'.$project->getEstimation().'D'));
		return null;
	}
}