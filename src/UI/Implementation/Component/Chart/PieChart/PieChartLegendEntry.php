<?php

namespace ILIAS\UI\Implementation\Component\Chart\PieChart;


use ILIAS\Data\Color;
use ILIAS\UI\Component\Chart\ChartLegendEntry as ChartLegendEntryInterface;
use ILIAS\UI\Component\Chart\ChartPoint as ChartPointInterface;
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
	private $name;
	/**
	 * @var Color
	 */
	private $textColor;
	/**
	 * @var ChartPointInterface
	 */
	private $textPoint;
	/**
	 * @var Color
	 */
	private $rectColor;
	/**
	 * @var ChartPointInterface
	 */
	private $rectPoint;


	/**
	 * LegendEntry constructor
	 *
	 * @param string $name
	 * @param int    $numSections
	 * @param int    $index
	 * @param float  $rectSpacing
	 * @param float  $extraY
	 */
	public function __construct(string $name, int $numSections, int $index, float $rectSpacing, float $extraY) {
		$this->checkStringArg("name", $name);
		$this->checkIntArg("numSections", $numSections);
		$this->checkIntArg("index", $index);

		$this->textPoint = new ChartPoint(self::LEGEND_X_PERCENTAGE + $rectSpacing / 2, 0);
		$this->rectPoint = new ChartPoint(self::LEGEND_X_PERCENTAGE - $rectSpacing / 2, 0);
		$this->name = $name;
		$this->calcCoords($numSections, $index, $extraY);
		$this->calcSizes($numSections, $name);
	}


	/**
	 * @param int $numSections
	 * @param int $index
	 */
	private function calcCoords(int $numSections, int $index, float $extraY): void {
		// Max 1.0: 0%y to 100%y
		$range = 0.8;
		$topMargin = (1 - $range) / 2;

		$y = (($topMargin + ($index * ($range / ($numSections + 1)))) * 100) + $extraY;
		$this->textPoint = $this->textPoint->withY($y);
		$this->rectPoint = $this->rectPoint->withY($y);
	}


	/**
	 * @param int    $numSections
	 * @param string $title
	 */
	private function calcSizes(int $numSections, string $title): void {
		if ($numSections >= 10) {
			$this->square_size = 1.5;
			$this->text_y_percentage = $this->y_percentage + 4;
		} else {
			$this->square_size = 2;
			$this->text_y_percentage = $this->y_percentage + 4.5;
		}

		$this->text_size = 1.5;
	}


	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}


	/**
	 * @return Color
	 */
	public function getTextColor(): Color {
		return $this->textColor;
	}


	/**
	 * @return ChartPointInterface
	 */
	public function getTextPoint(): ChartPointInterface {
		return $this->textPoint;
	}


	/**
	 * @return Color
	 */
	public function getRectColor(): Color {
		return $this->rectColor;
	}


	/**
	 * @return ChartPointInterface
	 */
	public function getRectPoint(): ChartPointInterface {
		return $this->rectPoint;
	}
}
