<?php

namespace ILIAS\UI\Component\Chart\PieChart;

use ILIAS\UI\Component\Chart\ChartItem;
use ILIAS\UI\Component\Chart\ChartLabel;

/**
 * Interface Section
 *
 * @package ILIAS\UI\Component\Chart\PieChart
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface Section extends ChartItem {

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
	 * Get the label on top of a section
	 *
	 * @return ChartLabel
	 */
	public function getSectionLabel(): ChartLabel;
}
