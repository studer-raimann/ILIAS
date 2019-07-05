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
	 * @return ChartPoint
	 */
	public function getPoint(): ChartPoint;

	/**
	 * @return string
	 */
	public function getText(): string;


	/**
	 * @return Color
	 */
	public function getColor(): Color;


	/**
	 * @return float
	 */
	public function getFontSize(): float;

}