<?php

namespace ILIAS\UI\Component\Chart;

use ILIAS\Data\Color;

/**
 * Interface ComparisonPath
 *
 * @package ILIAS\UI\Component\Chart
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface ChartComparisonPath {

	/**
	 * Retrieves the chart label which is used to display the comparison value
	 *
	 * @return ChartLabel
	 */
	public function getChartLabel(): ChartLabel;

	/**
	 * Retrieves the comparison value
	 *
	 * @return float
	 */
	public function getValue(): float;


	/**
	 * Retrieves points of the comparison path.
	 * e.g. a line has 2 points
	 *
	 * @return ChartPoint[]
	 */
	public function getPoints(): array;


	/**
	 * Retrieves the comparison path color
	 *
	 * @return Color
	 */
	public function getColor(): Color;

}