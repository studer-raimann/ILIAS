<?php

namespace ILIAS\UI\Component\Chart;

/**
 * Interface ChartPoint
 *
 * @package ILIAS\UI\Component\Chart
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface ChartPoint {

	/**
	 * @return float
	 */
	public function getX(): float;


	/**
	 * @return float
	 */
	public function getY(): float;


	/**
	 * @param float $x
	 *
	 * @return ChartPoint
	 */
	public function withX(float $x): self;


	/**
	 * @param float $y
	 *
	 * @return ChartPoint
	 */
	public function withY(float $y): self;

}