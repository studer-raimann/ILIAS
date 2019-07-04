<?php

namespace ILIAS\UI\Component\Chart;

/**
 * Interface Background
 *
 * @package ILIAS\UI\Component\Chart
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface ChartBackground {

	/**
	 * @return ChartComparisonPath[]
	 */
	public function getComparisonPaths(): array;

}