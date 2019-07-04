<?php

namespace ILIAS\UI\Component\Chart;

use ILIAS\Data\Color;

/**
 * Interface ChartLegendEntry
 *
 * @package ILIAS\UI\Component\Chart
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface ChartLegendEntry {

	const LEGEND_X_PERCENTAGE = 55;

	/**
	 * @return string
	 */
	public function getName(): string;


	/**
	 * @return Color
	 */
	public function getTextColor(): Color;


	/**
	 * @return ChartPoint
	 */
	public function getTextPoint(): ChartPoint;


	/**
	 * @return Color
	 */
	public function getRectColor(): Color;


	/**
	 * @return ChartPoint
	 */
	public function getRectPoint(): ChartPoint;

}