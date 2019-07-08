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
	 * Get the x coordinate (Usually percentage)
	 *
	 * @return float
	 */
	public function getX(): float;


	/**
	 * Get the y coordinate (Usually percentage)
	 *
	 * @return float
	 */
	public function getY(): float;


	/**
	 * Change the x coordinate and return a copy of the current object (Immutability)
	 *
	 * @param float $x
	 *
	 * @return ChartPoint
	 */
	public function withX(float $x): self;


	/**
	 * Change the y coordinate and return a copy of the current object (Immutability)
	 *
	 * @param float $y
	 *
	 * @return ChartPoint
	 */
	public function withY(float $y): self;

}