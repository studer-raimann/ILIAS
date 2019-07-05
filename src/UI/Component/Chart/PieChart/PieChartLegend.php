<?php

namespace ILIAS\UI\Component\Chart\PieChart;

use ILIAS\UI\Component\Chart\ChartLegend;

interface PieChartLegend extends ChartLegend {

	public function getExtraY(): float;


	public function getFontSize(): float;

}