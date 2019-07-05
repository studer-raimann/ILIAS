<?php

namespace ILIAS\UI\Component\Chart;

use ILIAS\Data\Color;

/**
 * Interface Chart
 *
 * @package ILIAS\UI\Component\Chart
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface Chart {

	/**
	 * Changes the state of the chart to either show or hide all legend entries
	 *
	 * @param bool $state
	 *
	 * @return Chart
	 */
	public function withShowLegend(bool $state): self;


	/**
	 * Changes the text color of a legend entry
	 *
	 * @param Color $color
	 *
	 * @return Chart
	 */
	public function withCustomLegendTextColor(Color $color): self;


	/**
	 * Gets the whole legend with its entries
	 *
	 * @return ChartLegend
	 */
	public function getLegend(): ChartLegend;


	/**
	 * Gets the data of the chart
	 *
	 * @return ChartItem[]
	 */
	public function getChartItems(): array;


	/**
	 * Checks the state of the flag which shows the legend
	 *
	 * @return bool
	 */
	public function isShowLegend(): bool;


	/**
	 * Gets the custom text color of a legend entry
	 *
	 * @return Color|null
	 */
	public function getCustomLegendTextColor(): ?Color;


}