<?php

namespace ILIAS\UI\Component\Chart;

/**
 * Interface ChartPoint
 *
 * @package ILIAS\UI\Component\Chart
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface ChartPoint {

	/**
	 * @return float
	 */
	public function getX(): float;


	/**
	 * @return float
	 */
	public function getY(): float;

}