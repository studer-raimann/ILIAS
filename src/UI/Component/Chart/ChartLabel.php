<?php

namespace ILIAS\UI\Component\Chart;

use ILIAS\Data\Color;

/**
 * Interface ChartLabel
 *
 * @package ILIAS\UI\Component\Chart
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface ChartLabel {

	/**
	 * Gets the coordinates of this label
	 *
	 * @return ChartPoint
	 */
	public function getPoint(): ChartPoint;

	/**
	 * Gets the text to be displayed of this label
	 *
	 * @return string
	 */
	public function getText(): string;


	/**
	 * Gets the text color of this label to be displayed
	 *
	 * @return Color
	 */
	public function getColor(): Color;


	/**
	 * Gets the font size of this label
	 *
	 * @return float
	 */
	public function getFontSize(): float;

}