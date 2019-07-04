<?php

namespace ILIAS\UI\Implementation\Component\Chart\PieChart;

use ILIAS\UI\Component\Chart\ChartLegend;
use ILIAS\UI\Component\Chart\ChartLegendEntry as ChartLegendEntryInterface;
use ILIAS\UI\Component\Chart\PieChart\PieChartItem as PieChartItemInterface;

/**
 * Class Legend
 *
 * @package ILIAS\UI\Implementation\Component\Chart\PieChart
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class PieChartLegend implements ChartLegend {

	/**
	 * @var ChartLegendEntryInterface[]
	 */
	protected $entries = [];
	/**
	 * @var float
	 */
	protected $rectSize;
	/**
	 * @var float
	 */
	protected $rectSpacing;


	/**
	 * PieChartLegend constructor.
	 *
	 * @param PieChartItemInterface[] $sampleItems
	 */
	public function __construct(array $sampleItems) {
		$this->calcSizes(count($sampleItems));

		for ($i = 0; $i < count($sampleItems); $i++) {
			$sampleItem = $sampleItems[$i];
			$itemCount = count($sampleItems);
			$index = $i;
			$rectSpacing = $this->rectSpacing;
			$extraY = 4;
			$this->entries[] = new PieChartLegendEntry($sampleItem->getName(), $itemCount, $index, $rectSpacing, $extraY);
		}
	}


	private function calcSizes(int $numSections): void {
		if ($numSections >= 10) {
			$this->rectSize = 1.5;
		} else {
			$this->rectSize = 2;
		}

	}


	/**
	 * @return ChartLegendEntryInterface[]
	 */
	public function getEntries(): array {
		return $this->entries;
	}


	/**
	 * @return float
	 */
	public function getRectSize(): float {
		return $this->rectSize;
	}


	/**
	 * @return float
	 */
	public function getRectSpacing(): float {
		return $this->rectSpacing;
	}
}