<?php

namespace Lks\ManPowerBundle\Utility;
/**
 * The aim of this class is to provide a list of function to manipulate the Date.
 */
class DateUtility
{
	public function __construct()
	{

	}	

	/**
	 * Compute the period from the beginDate and the duration.
	 * 
	 * @param beginDate Start date for the period.
	 * @param duration Number of day.
	 * @param withWeekEnd True if the WeekEnd is included, false else.
	 *
	 * @return ArrayCollection
	 */
	public function getPeriod($beginDate, $duration, $withWeekEnd = true)
	{
		$oneday = new \DateInterval("P1D");
		$period = null;
		$nbDays = 0;

		if($beginDate != null && $duration != null)
		{
			$period = array();
			$startDate = clone $beginDate;
			while($nbDays != $duration)
			{
				if($withWeekEnd || (!$withWeekEnd && $startDate->format('N') != 6 && $startDate->format('N') != 7))
				{
					array_push($period, clone $startDate);
					$nbDays++;
				}
				$startDate->add($oneday);
			}
		}
		
		return $period;
	}

	/**
	 * Get the end date in function of a beginDate, a duration and the weekend included or not.
	 * 
	 * @param beginDate Start date for the period.
	 * @param duration Number of day.
	 * @param withWeekEnd True if the WeekEnd is included, false else.
	 *
	 * @return DateTime
	 */
	public function getEndDate($beginDate, $duration, $withWeekEnd = true)
	{
		$endDate = null;
		$period = $this->getPeriod($beginDate, $duration, $withWeekEnd);
		if($period != null)
		{
			$endDate = end($period);
		}
		return $endDate;
	}
}