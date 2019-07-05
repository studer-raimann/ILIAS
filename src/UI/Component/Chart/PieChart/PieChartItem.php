<?php

namespace ILIAS\UI\Component\Chart\PieChart;

use ILIAS\Data\Color;
use ILIAS\UI\Component\Chart\ChartItem;

/**
 * Interface PieChartItem
 *
 * @package ILIAS\UI\Component\Chart\PieChart
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface PieChartItem extends ChartItem {

	const MAX_TITLE_CHARS = 35;
	const MAX_VALUES = 1;
	const ERR_TOO_MANY_CHARS = "More than " . self::MAX_TITLE_CHARS . " characters in the title";
	const ERR_TOO_MANY_VALUES = "More than " . self::MAX_VALUES . " value per PieChartItem (max) supplied";

	/**
	 * Get the text color of a pre-section. The default is black.
	 *
	 * @return Color
	 */
	public function getTextColor(): Color;
}
