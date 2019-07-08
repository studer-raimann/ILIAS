<?php

namespace ILIAS\UI\Component\Chart;

use ILIAS\Data\Color;

/**
 * Interface ChartValue
 *
 * @package ILIAS\UI\Component\Chart
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface ChartItem {

	/**
	 * Gets the main values of a chart.
	 * e.g. a PieChart section only has 1 value per item while a line diagram has multiple per item (line)
	 *
	 * @return float[]
	 */
	public function getValues(): array;


	/**
	 * Gets the color of the chart item
	 *
	 * @return Color
	 */
	public function getColor(): Color;


	/**
	 * Gets the name of the chart item
	 *
	 * @return string
	 */
	public function getName(): string;

}