<?php

namespace ILIAS\UI\Component\Chart;

/**
 * Interface Chart
 *
 * @package ILIAS\UI\Component\Chart
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface Chart {

	/**
	 * @return string
	 */
	public function getTitle(): string;


	/**
	 * @return ChartLegend
	 */
	public function getLegend(): ChartLegend;


	/**
	 * @return ChartItem[]
	 */
	public function getChartItems(): array;


	/**
	 * @return Background|null
	 */
	public function getBackground(): ?Background;

}