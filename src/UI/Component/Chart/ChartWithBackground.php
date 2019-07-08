<?php

namespace ILIAS\UI\Component\Chart;

use ILIAS\Data\Color;

interface ChartWithBackground extends Chart {

	/**
	 * Get the background with all the comparison values
	 *
	 * @return ChartBackground
	 */
	public function getBackground(): ChartBackground;


	/**
	 * Change the custom comparison path color, which is used to alter the comparison path colors of the background
	 *
	 * @param Color $color
	 *
	 * @return ChartWithBackground
	 */
	public function withCustomComparisonPathColor(Color $color): self;


	/**
	 * Change the custom comparison path label color, which is used to alter the color of labels assigned to comparison paths
	 *
	 * @param Color $color
	 *
	 * @return ChartWithBackground
	 */
	public function withCustomComparisonPathLabelColor(Color $color): self;


	/**
	 * Get the custom comparison path color, if available
	 *
	 * @return Color
	 */
	public function getCustomComparisonPathColor(): ?Color;


	/**
	 * Get the custom comparison path label color, if available
	 *
	 * @return Color
	 */
	public function getCustomComparisonPathLabelColor(): ?Color;

}