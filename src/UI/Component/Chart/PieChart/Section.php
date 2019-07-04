<?php

namespace ILIAS\UI\Component\Chart\PieChart;

use ILIAS\Data\Color;
use ILIAS\UI\Component\Chart\ChartItem;

/**
 * Interface Section
 *
 * @package ILIAS\UI\Component\Chart\PieChart
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface Section extends ChartItem {

	/**
	 * Set the text color of the value that is on top of the section
	 *
	 * @param Color $textColor
	 *
	 * @return self
	 */
	public function withTextColor(Color $textColor): self;

	/**
	 * Get the percentage this section takes up compared to the total of all sections
	 *
	 * @return float
	 */
	public function getPercentage(): float;


	/**
	 * Get the stroke length of the section. The way sections get displayed (Pure CSS method) is by using dashed lines. All you see from sections
	 * are the dashed lines and they are quite thick. The stroke length defines how long a dashed line is. Basically how long a section is.
	 *
	 * @return float
	 */
	public function getStrokeLength(): float;


	/**
	 * Get the offset from the start. The way sections get displayed (Pure CSS method) is by using dashed lines. All you see from sections
	 * are the dashed lines and they are quite thick. The offset defines where the dash of a stroke will begin.
	 *
	 * @return float
	 */
	public function getOffset(): float;


	/**
	 * Get the text color of the value that is on top of the section
	 *
	 * @return Color
	 */
	public function getTextColor(): Color;
}
