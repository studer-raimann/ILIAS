<?php

namespace ILIAS\UI\Component\Chart;

/**
 * Interface ComparisonPath
 *
 * @package ILIAS\UI\Component\Chart
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface ComparisonPath {

	/**
	 * @return float
	 */
	public function getValue(): float;


	/**
	 * @return ChartPoint[]
	 */
	public function getPoints(): array;

}