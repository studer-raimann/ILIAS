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
interface ChartLegendEntry extends ChartLabel {

	const LEGEND_X_PERCENTAGE = 55;


	/**
	 * Get the rectangle color
	 *
	 * @return Color
	 */
	public function getRectColor(): Color;


	/**
	 * Get the coordinates of the rectangle
	 *
	 * @return ChartPoint
	 */
	public function getRectPoint(): ChartPoint;

}