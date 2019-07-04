<?php

namespace ILIAS\UI\Component\Chart\PieChart;

use ILIAS\UI\Component\Chart\ChartPoint;

/**
 * Interface SectionValue
 *
 * @package ILIAS\UI\Component\Chart\PieChart
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface SectionValue extends ChartPoint {

	/**
	 * Get the actual value
	 *
	 * @return float
	 */
	public function getValue(): float;


	/**
	 * Get the size of the value text (On to pof the pie chart section)
	 *
	 * @return int
	 */
	public function getTextSize(): int;
}
