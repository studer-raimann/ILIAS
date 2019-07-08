<?php

namespace ILIAS\UI\Implementation\Component\Chart\PieChart;


use ILIAS\Data\Color;
use ILIAS\UI\Component\Chart\ChartLegendEntry as ChartLegendEntryInterface;
use ILIAS\UI\Component\Chart\ChartPoint as ChartPointInterface;
use ILIAS\UI\Component\Chart\PieChart\PieChartItem as PieChartItemInterface;
use ILIAS\UI\Implementation\Component\Chart\ChartPoint;
use ILIAS\UI\Implementation\Component\ComponentHelper;

/**
 * Class LegendEntry
 *
 * @package ILIAS\UI\Implementation\Component\Chart\PieChart
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class PieChartLegendEntry implements ChartLegendEntryInterface {

	use ComponentHelper;
	/**
	 * @var string
	 */
	protected $text;
	/**
	 * @var Color
	 */
	protected $color;
	/**
	 * @var float
	 */
	protected $fontSize;
	/**
	 * @var ChartPointInterface
	 */
	protected $point;
	/**
	 * @var Color
	 */
	protected $rectColor;
	/**
	 * @var ChartPointInterface
	 */
	protected $rectPoint;


	/**
	 * LegendEntry constructor
	 *
	 * @param PieChartItemInterface $item
	 * @param float                 $fontSize
	 * @param int                   $numSections
	 * @param int                   $index
	 * @param float                 $rectSpacing
	 * @param float                 $extraY
	 */
	public function __construct(PieChartItemInterface $item, float $fontSize, int $numSections, int $index, float $rectSpacing, float $extraY) {
		$this->fontSize = $fontSize;
		$this->point = new ChartPoint(self::LEGEND_X_PERCENTAGE + $rectSpacing / 2, 0);
		$this->rectPoint = new ChartPoint(self::LEGEND_X_PERCENTAGE - $rectSpacing / 2, 0);
		$this->text = $item->getName();
		$this->rectColor = $item->getColor();
		$this->calcCoords($numSections, $index, $extraY);
		$this->color = new Color(0,0,0);
	}


	/**
	 * @param int   $numSections
	 * @param int   $index
	 * @param float $extraY
	 */
	private function calcCoords(int $numSections, int $index, float $extraY): void {
		// Max 1.0: 0%y to 100%y
		$range = 0.8;
		$topMargin = (1 - $range) / 2;

		$y = (($topMargin + (($index + 1) * ($range / ($numSections + 1)))) * 100);
		$this->point = $this->point->withY($y + $extraY / 2);
		$this->rectPoint = $this->rectPoint->withY($y - $extraY / 2);
	}


	/**
	 * @inheritDoc
	 */
	public function getRectColor(): Color {
		return $this->rectColor;
	}


	/**
	 * @inheritDoc
	 */
	public function getRectPoint(): ChartPointInterface {
		return $this->rectPoint;
	}


	/**
	 * @inheritDoc
	 */
	public function getText(): string {
		return $this->text;
	}


	/**
	 * @inheritDoc
	 */
	public function getColor(): Color {
		return $this->color;
	}


	/**
	 * @inheritDoc
	 */
	public function getFontSize(): float {
		return $this->fontSize;
	}


	/**
	 * @inheritDoc
	 */
	public function getPoint(): ChartPointInterface {
		return $this->point;
	}
}
