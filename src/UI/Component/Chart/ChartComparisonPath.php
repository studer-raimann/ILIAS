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
	 * @return ChartLabel
	 */
	public function getChartLabel(): ChartLabel;

	/**
	 * @return float
	 */
	public function getValue(): float;


	/**
	 * @return ChartPoint[]
	 */
	public function getPoints(): array;


	/**
	 * @return Color
	 */
	public function getColor(): Color;

}