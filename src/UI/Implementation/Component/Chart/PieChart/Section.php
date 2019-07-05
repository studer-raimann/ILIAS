<?php

namespace ILIAS\UI\Implementation\Component\Chart\PieChart;

use ILIAS\Data\Color;
use ILIAS\UI\Component\Chart\ChartLabel;
use ILIAS\UI\Component\Chart\PieChart\PieChartItem as PieChartItemInterface;
use ILIAS\UI\Component\Chart\PieChart\Section as SectionInterface;
use ILIAS\UI\Component\Chart\PieChart\SectionLabel as SectionLabelInterface;
use ILIAS\UI\Component\Chart\PieChart\SectionValue as SectionValueInterface;
use ILIAS\UI\Implementation\Component\ComponentHelper;

/**
 * Class Section
 *
 * @package ILIAS\UI\Implementation\Component\Chart\PieChart
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Section implements SectionInterface {

	use ComponentHelper;
	/**
	 * @var string
	 */
	protected $name;
	/**
	 * @var float[]
	 */
	protected $values = [];
	/**
	 * @var float
	 */
	protected $percentage;
	/**
	 * @var float
	 */
	protected $stroke_length;
	/**
	 * @var float
	 */
	protected $offset;
	/**
	 * @var Color
	 */
	protected $color;
	/**
	 * @var ChartLabel
	 */
	protected $sectionLabel;


	/**
	 * Section constructor
	 *
	 * @param PieChartItemInterface $item
	 * @param float                 $totalValue
	 * @param float                 $offset
	 */
	public function __construct(PieChartItemInterface $item, float $totalValue, float $offset) {
		$name = $item->getName();
		$values = $item->getValues();
		$color = $item->getColor();
		$this->name = $name;
		$this->color = $color;
		$this->offset = $offset;

		$this->calcPercentage($totalValue, $values[0]);
		$this->calcStrokeLength();

		$this->sectionLabel = new SectionLabel($values[0], $this->stroke_length, $this->offset, $this->percentage);
	}


	/**
	 * @param float $totalValue
	 * @param float $sectionValue
	 */
	private function calcPercentage(float $totalValue, float $sectionValue): void {
		$this->percentage = $sectionValue / $totalValue * 100;
	}


	/**
	 *
	 */
	private function calcStrokeLength(): void {
		$this->stroke_length = $this->percentage / 2.549;
	}


	/**
	 * @inheritDoc
	 */
	public function getName(): string {
		return $this->name;
	}


	/**
	 * @inheritDoc
	 */
	public function getPercentage(): float {
		return $this->percentage;
	}


	/**
	 * @inheritDoc
	 */
	public function getStrokeLength(): float {
		return $this->stroke_length;
	}


	/**
	 * @inheritDoc
	 */
	public function getOffset(): float {
		return $this->offset;
	}


	/**
	 * @inheritDoc
	 */
	public function getColor(): Color {
		return $this->color;
	}


	/**
	 * @return float[]
	 */
	public function getValues(): array {
		return $this->values;
	}


	/**
	 * @inheritDoc
	 *
	 * @return ChartLabel
	 */
	public function getSectionLabel(): ChartLabel {
		return $this->sectionLabel;
	}

}
