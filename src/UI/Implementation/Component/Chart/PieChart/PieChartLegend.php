<?php

namespace ILIAS\UI\Implementation\Component\Chart\PieChart;

use ILIAS\UI\Component\Chart\ChartLegendEntry as ChartLegendEntryInterface;
use ILIAS\UI\Component\Chart\PieChart\PieChartItem as PieChartItemInterface;
use ILIAS\UI\Component\Chart\PieChart\PieChartLegend as PieChartLegendInterface;

/**
 * Class Legend
 *
 * @package ILIAS\UI\Implementation\Component\Chart\PieChart
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class PieChartLegend implements PieChartLegendInterface {

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
	protected $rectSpacing = 5;
	/**
	 * @var float
	 */
	protected $extraY;
	/**
	 * @var float
	 */
	protected $fontSize = 1.5;


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
			$fontSize = $this->fontSize;
			$index = $i;
			$rectSpacing = $this->rectSpacing;
			$extraY = $this->extraY;
			$this->entries[] = new PieChartLegendEntry($sampleItem, $fontSize, $itemCount, $index, $rectSpacing, $extraY);
		}
	}


	private function calcSizes(int $numSections): void {
		if ($numSections >= 10) {
			$this->rectSize = 1.5;
			$this->extraY = 4;
		} else {
			$this->rectSize = 2;
			$this->extraY = 4.5;
		}

	}


	/**
	 * @inheritDoc
	 */
	public function getEntries(): array {
		return $this->entries;
	}


	/**
	 * @inheritDoc
	 */
	public function getRectSize(): float {
		return $this->rectSize;
	}


	/**
	 * @inheritDoc
	 */
	public function getRectSpacing(): float {
		return $this->rectSpacing;
	}


	/**
	 * @inheritDoc
	 */
	public function getExtraY(): float {
		return $this->extraY;
	}


	/**
	 * @inheritDoc
	 */
	public function getFontSize(): float {
		return $this->fontSize;
	}
}