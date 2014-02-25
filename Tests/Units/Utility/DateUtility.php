<?php

namespace Lks\ManPowerBundle\Tests\Units\Utility;

use atoum\AtoumBundle\Test\Units;

class DateUtility extends Units\Test
{
	public function testGetPeriod_NominalCase()
	{
		$dateUtility = new \Lks\ManPowerBundle\Utility\DateUtility();

		//First case: Nominal case
		$period = $dateUtility->getPeriod(new \DateTime("2013-10-14"), 2);
		$this
			->array($period)
				->isNotEmpty()
				->hasSize(2)
			->dateTime($period[0])
				->hasDate('2013', '10', '14')
			->dateTime($period[1])
				->hasDate('2013', '10', '15')
		;
	}

	public function testGetPeriod_WithWeekEndCase()
	{
		$dateUtility = new \Lks\ManPowerBundle\Utility\DateUtility();

		$period = $dateUtility->getPeriod(new \DateTime("2013-10-18"), 2);
		$this
			->array($period)
				->isNotEmpty()
				->hasSize(2)
			->dateTime($period[0])
				->hasDate('2013', '10', '18')
			->dateTime($period[1])
				->hasDate('2013', '10', '19')
		;
	}

	public function testGetPeriod_WithoutWeekEndCase()
	{
		$dateUtility = new \Lks\ManPowerBundle\Utility\DateUtility();

		$period = $dateUtility->getPeriod(new \DateTime("2013-10-18"), 2, false);
		$this
			->array($period)
				->isNotEmpty()
				->hasSize(2)
			->dateTime($period[0])
				->hasDate('2013', '10', '18')
			->dateTime($period[1])
				->hasDate('2013', '10', '21')
		;
	}

	public function testGetPeriod_StartDateNull()
	{
		$dateUtility = new \Lks\ManPowerBundle\Utility\DateUtility();

		$period = $dateUtility->getPeriod(null, 2);
		$this
			->variable($period)
				->isNull()
		;
	}

	public function testGetPeriod_DurationNull()
	{
		$dateUtility = new \Lks\ManPowerBundle\Utility\DateUtility();

		$period = $dateUtility->getPeriod(new \DateTime("2013-10-18"), null);
		$this
			->variable($period)
				->isNull()
		;
	}

	public function testGetEndDate_NominalCase()
	{
		$dateUtility = new \Lks\ManPowerBundle\Utility\DateUtility();

		$endDate = $dateUtility->getEndDate(new \DateTime("2013-10-14"), 2);
		$this
			->dateTime($endDate)
				->hasDate('2013', '10', '15')
		;
	}

	public function testGetEndDate_WithWeekEndCase()
	{
		$dateUtility = new \Lks\ManPowerBundle\Utility\DateUtility();

		$endDate = $dateUtility->getEndDate(new \DateTime("2013-10-18"), 2);
		$this
			->dateTime($endDate)
				->hasDate('2013', '10', '19')
		;
	}

	public function testGetEndDate_WithoutWeekEndCase()
	{
		$dateUtility = new \Lks\ManPowerBundle\Utility\DateUtility();

		$endDate = $dateUtility->getEndDate(new \DateTime("2013-10-18"), 2, false);
		$this
			->dateTime($endDate)
				->hasDate('2013', '10', '21')
		;
	}

	public function testGetEndDate_StartDateNull()
	{
		$dateUtility = new \Lks\ManPowerBundle\Utility\DateUtility();

		$endDate = $dateUtility->getEndDate(null, 2);
		$this
			->variable($endDate)
				->isNull()
		;
	}

	public function testGetEndDate_DurationNull()
	{
		$dateUtility = new \Lks\ManPowerBundle\Utility\DateUtility();

		$endDate = $dateUtility->getEndDate(new \DateTime("2013-10-18"), null);
		$this
			->variable($endDate)
				->isNull()
		;
	}
}