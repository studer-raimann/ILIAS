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
	 * @return ChartLegendEntry[]
	 */
	public function getEntries(): array;


	/**
	 * @return float
	 */
	public function getRectSize(): float;


	/**
	 * @return float
	 */
	public function getRectSpacing(): float;

}