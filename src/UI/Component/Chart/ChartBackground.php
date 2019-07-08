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
	 * Retrieves all comparison paths
	 *
	 * @return ChartComparisonPath[]
	 */
	public function getComparisonPaths(): array;

}