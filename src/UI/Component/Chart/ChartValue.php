<?php

namespace ILIAS\UI\Component\Chart;

use ILIAS\Data\Color;

/**
 * Interface ChartValue
 *
 * @package ILIAS\UI\Component\Chart
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface ChartValue {

	/**
	 * @return float
	 */
	public function getValue(): float;


	/**
	 * @return Color
	 */
	public function getColor(): Color;


	/**
	 * @return string
	 */
	public function getName(): string;

}