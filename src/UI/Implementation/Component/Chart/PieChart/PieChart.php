<?php

namespace ILIAS\UI\Implementation\Component\Chart\PieChart;

use ILIAS\Data\Color;
use ILIAS\UI\Component\Chart\Chart;
use ILIAS\UI\Component\Chart\ChartLegend;
use ILIAS\UI\Component\Chart\PieChart\PieChart as PieChartInterface;
use ILIAS\UI\Component\Chart\PieChart\PieChartItem as PieChartItemInterface;
use ILIAS\UI\Component\Chart\PieChart\Section as SectionInterface;
use ILIAS\UI\Implementation\Component\ComponentHelper;
use InvalidArgumentException;

/**
 * Class PieChart
 *
 * @package ILIAS\UI\Implementation\Component\Chart\PieChart
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class PieChart implements PieChartInterface {

	use ComponentHelper;
	/**
	 * @var ChartLegend
	 */
	protected $legend;
	/**
	 * @var SectionInterface[]
	 */
	protected $chartItems = [];
	/**
	 * @var float
	 */
	protected $totalValue = 0;
	/**
	 * @var bool
	 */
	protected $showLegend = true;
	/**
	 * @var float|null
	 */
	protected $customTotalValue = NULL;
	/**
	 * @var Color|null
	 */
	protected $customLegendTextColor = NULL;
	/**
	 * @var Color|null
	 */
	protected $customSectionLabelColor = NULL;
	/**
	 * @var Color|null
	 */
	protected $customTotalLabelColor = NULL;


	/**
	 * PieChart constructor
	 *
	 * @param PieChartItemInterface[] $pieChartItems
	 */
	public function __construct(array $pieChartItems) {
		if (count($pieChartItems) === 0) {
			throw new InvalidArgumentException(self::ERR_NO_ITEMS);
		} else {
			if (count($pieChartItems) > self::MAX_ITEMS) {
				throw new InvalidArgumentException(self::ERR_TOO_MANY_ITEMS);
			}
		}

		$this->calcTotalValue($pieChartItems);
		$this->createSections($pieChartItems);
		$this->legend = new PieChartLegend($pieChartItems);
	}


	/**
	 * @param PieChartItemInterface[] $pieChartItems
	 */
	protected function createSections(array $pieChartItems): void {
		$currentOffset = 0;
		$index = 1;

		foreach ($pieChartItems as $item) {
			$section = new Section($item, $this->totalValue, $currentOffset);
			$this->chartItems[] = $section;
			$currentOffset += $section->getStrokeLength();
			$index ++;
		}
	}


	/**
	 * @param PieChartItemInterface[] $pieChartItems
	 */
	protected function calcTotalValue(array $pieChartItems): void {
		$total = 0;
		foreach ($pieChartItems as $item) {
			$total += $item->getValues()[0];
		}
		$this->totalValue = $total;
	}


	/**
	 * @inheritDoc
	 */
	public function getTotalValue(): float {
		return $this->totalValue;
	}


	/**
	 * @inheritDoc
	 */
	public function isShowLegend(): bool {
		return $this->showLegend;
	}


	/**
	 * @inheritDoc
	 */
	public function withCustomTotalValue(?float $custom_total_value = NULL): PieChartInterface {
		if (!is_null($custom_total_value)) {
			$this->checkFloatArg("custom_total_value", $custom_total_value);
		}
		$clone = clone $this;
		$clone->customTotalValue = $custom_total_value;

		return $clone;
	}


	/**
	 * @inheritDoc
	 */
	public function withCustomLegendTextColor(Color $color): Chart {
		$clone = clone $this;
		$clone->customLegendTextColor = $color;

		return $clone;
	}


	/**
	 * @inheritDoc
	 */
	public function withShowLegend(bool $state): Chart {
		$clone = clone $this;
		$clone->showLegend = $state;

		return $clone;
	}


	/**
	 * @inheritDoc
	 */
	public function getCustomTotalValue(): ?float {
		return $this->customTotalValue;
	}


	/**
	 * @inheritDoc
	 */
	public function getLegend(): ChartLegend {
		return $this->legend;
	}


	/**
	 * @inheritDoc
	 */
	public function getChartItems(): array {
		return $this->chartItems;
	}


	/**
	 * @inheritDoc
	 */
	public function getCustomLegendTextColor(): ?Color {
		return $this->customLegendTextColor;
	}


	/**
	 * @inheritDoc
	 */
	public function withCustomSectionLabelColor(Color $color): PieChartInterface {
		$clone = clone $this;
		$clone->customSectionLabelColor = $color;

		return $clone;
	}


	/**
	 * @inheritDoc
	 */
	public function withCustomTotalLabelColor(Color $color): PieChartInterface {
		$clone = clone $this;
		$clone->customTotalLabelColor = $color;

		return $clone;
	}


	/**
	 * @inheritDoc
	 */
	public function getCustomSectionLabelColor(): ?Color {
		return $this->customSectionLabelColor;
	}


	/**
	 * @inheritDoc
	 */
	public function getCustomTotalLabelColor(): ?Color {
		return $this->customTotalLabelColor;
	}
}
