<?php

namespace ILIAS\UI\Component\Chart\PieChart;

use ILIAS\Data\Color;
use ILIAS\UI\Component\Chart\Chart;
use ILIAS\UI\Component\Chart\ChartWithoutBackground;
use ILIAS\UI\Component\Component;

/**
 * Interface PieChart
 *
 * @package ILIAS\UI\Component\Chart\PieChart
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface PieChart extends ChartWithoutBackground, Component {

	const MAX_ITEMS = 12;
	const ERR_NO_ITEMS = "Empty array supplied as argument";
	const ERR_TOO_MANY_ITEMS = "More than " . self::MAX_ITEMS . " Pie Chart Items supplied";


	/**
	 * @return Section[]
	 */
	public function getChartItems(): array;


	/**
	 * Get the combined value of all sections that is shown in the center
	 *
	 * @return float
	 */
	public function getTotalValue(): float;


	/**
	 * @param float|null $custom_total_value
	 *
	 * @return self
	 */
	public function withCustomTotalValue(?float $custom_total_value = null): self;


	/**
	 * @param bool $state
	 *
	 * @return self
	 */
	public function withShowLegend(bool $state): Chart;


	/**
	 * @param Color $color
	 *
	 * @return self
	 */
	public function withCustomLegendTextColor(Color $color): Chart;


	/**
	 * @param Color $color
	 *
	 * @return PieChart
	 */
	public function withCustomSectionLabelColor(Color $color): self;


	/**
	 * @param Color $color
	 *
	 * @return PieChart
	 */
	public function withCustomTotalLabelColor(Color $color): self;


	/**
	 * @return float|null
	 */
	public function getCustomTotalValue(): ?float;


	/**
	 * @return Color|null
	 */
	public function getCustomSectionLabelColor(): ?Color;


	/**
	 * @return Color|null
	 */
	public function getCustomTotalLabelColor(): ?Color;
}
