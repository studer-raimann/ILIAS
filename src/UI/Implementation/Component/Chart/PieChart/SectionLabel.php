<?php

namespace ILIAS\UI\Implementation\Component\Chart\PieChart;

use ILIAS\Data\Color;
use ILIAS\UI\Component\Chart\ChartLabel as ChartLabelInterface;
use ILIAS\UI\Component\Chart\ChartPoint as ChartPointInterface;
use ILIAS\UI\Implementation\Component\Chart\ChartPoint;

class SectionLabel implements ChartLabelInterface {

	/**
	 * @var ChartPointInterface
	 */
	protected $point;
	/**
	 * @var string
	 */
	protected $text;
	/**
	 * @var float
	 */
	protected $fontSize;
	/**
	 * @var Color
	 */
	protected $color;


	/**
	 * SectionLabel constructor.
	 *
	 * @param float      $value
	 * @param float      $stroke_dasharray
	 * @param float      $stroke_dashoffset
	 * @param float      $section_percentage
	 * @param Color|NULL $textColor
	 */
	public function __construct(float $value, float $stroke_dasharray, float $stroke_dashoffset, float $section_percentage, Color $textColor = NULL) {
		if (is_null($textColor)) {
			$this->color = new Color(0, 0, 0);
		} else {
			$this->color = $textColor;
		}
		$this->text = round($value, 2);
		$this->calcChartCoords($stroke_dasharray, $stroke_dashoffset);
		$this->calcTextSize($section_percentage);
	}


	/**
	 * @param float $stroke_dasharray
	 * @param float $stroke_dashoffset
	 */
	private function calcChartCoords(float $stroke_dasharray, float $stroke_dashoffset): void {
		$angle_dasharray = abs($stroke_dasharray) * 3.6 * 2.549;
		$angle_dashoffset = abs($stroke_dashoffset) * 3.6 * 2.549;
		$final_angle_rad = deg2rad(360 - ($angle_dashoffset + $angle_dasharray / 2));

		$x = (0.25 + (cos($final_angle_rad) * 0.135)) * 100;
		$y = (0.5 - (sin($final_angle_rad) * 0.275)) * 100;

		$this->point = new ChartPoint($x, $y);
	}


	/**
	 * @param float $section_percentage
	 */
	private function calcTextSize(float $section_percentage): void {
		if ($section_percentage <= 7) {
			$this->fontSize = 0;
		} else {
			$this->fontSize = 3;
		}
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
	public function getColor(): Color {
		return $this->color;
	}


	/**
	 * @inheritDoc
	 */
	public function getPoint(): ChartPointInterface {
		return $this->point;
	}


	/**
	 * @inheritDoc
	 */
	public function getText(): string {
		return $this->text;
	}
}