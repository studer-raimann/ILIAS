<?php

namespace ILIAS\UI\Component\Chart;

/**
 * Interface ChartLegend
 *
 * @package ILIAS\UI\Component\Chart
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface ChartLegend {

	/**
	 * Gets all entries of the legend
	 *
	 * @return ChartLegendEntry[]
	 */
	public function getEntries(): array;


	/**
	 * Gets the generic rectangle size of legend entries
	 *
	 * @return float
	 */
	public function getRectSize(): float;


	/**
	 * Gets the generic space between name and rectangle in legend entries
	 *
	 * @return float
	 */
	public function getRectSpacing(): float;

}