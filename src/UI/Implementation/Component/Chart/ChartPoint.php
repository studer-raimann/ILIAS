<?php

namespace ILIAS\UI\Implementation\Component\Chart;

use ILIAS\UI\Component\Chart\ChartPoint as ChartPointInterface;

/**
 * Class ChartPoint
 *
 * @package ILIAS\UI\Implementation\Component\Chart
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ChartPoint implements ChartPointInterface {

	/**
	 * @var float
	 */
	protected $x;
	/**
	 * @var float
	 */
	protected $y;


	/**
	 * ChartPoint constructor.
	 *
	 * @param float $x
	 * @param float $y
	 */
	public function __construct(float $x, float $y) {
		$this->x = $x;
		$this->y = $y;
	}


	/**
	 * @return float
	 */
	public function getX(): float {
		return $this->x;
	}


	/**
	 * @return float
	 */
	public function getY(): float {
		return $this->y;
	}


	/**
	 * @param float $x
	 *
	 * @return ChartPointInterface
	 */
	public function withX(float $x): ChartPointInterface {
		$clone = clone $this;
		$clone->x = $x;

		return $clone;
	}


	/**
	 * @param float $y
	 *
	 * @return ChartPointInterface
	 */
	public function withY(float $y): ChartPointInterface {
		$clone = clone $this;
		$clone->y = $y;

		return $clone;
	}
}