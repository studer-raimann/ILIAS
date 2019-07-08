<?php

namespace ILIAS\UI\Component\Chart\PieChart;

use ILIAS\UI\Component\Chart\ChartLegend;

/**
 * Interface PieChartLegend
 *
 * @package ILIAS\UI\Component\Chart\PieChart
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface PieChartLegend extends ChartLegend {

	/**
	 * Get the extra y to combat the font size text shift
	 *
	 * @return float
	 */
	public function getExtraY(): float;


	/**
	 * Get the font size
	 *
	 * @return float
	 */
	public function getFontSize(): float;
}