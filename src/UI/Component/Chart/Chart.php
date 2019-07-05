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
	 * @param bool $state
	 *
	 * @return Chart
	 */
	public function withShowLegend(bool $state): self;


	/**
	 * @param Color $color
	 *
	 * @return Chart
	 */
	public function withCustomLegendTextColor(Color $color): self;


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
	 * @return bool
	 */
	public function isShowLegend(): bool;


	/**
	 * @return Color|null
	 */
	public function getCustomLegendTextColor(): ?Color;


}