<?php

namespace ILIAS\UI\Component\Chart;

use ILIAS\Data\Color;

interface ChartWithBackground extends Chart {

	/**
	 * @return ChartBackground
	 */
	public function getBackground(): ChartBackground;


	/**
	 * @param Color $color
	 *
	 * @return ChartWithBackground
	 */
	public function withCustomComparisonPathColor(Color $color): self;


	/**
	 * @param Color $color
	 *
	 * @return ChartWithBackground
	 */
	public function withCustomComparisonPathLabelColor(Color $color): self;


	/**
	 * @return Color
	 */
	public function getCustomComparisonPathColor(): Color;


	/**
	 * @return Color
	 */
	public function getCustomComparisonPathLabelColor(): Color;

}