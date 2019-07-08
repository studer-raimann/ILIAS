<?php

use ILIAS\Data\Color;
use ILIAS\UI\Implementation\Component\Chart\PieChart\PieChartItem;

/**
 * @return string
 */
function custom_total_label_color(): string {
	global $DIC;

	$c = $DIC->ui()->factory()->chart()->pieChart([
		new PieChartItem("One", [5.4], new Color(55, 0, 0), new Color(200, 200, 200)),
		new PieChartItem("Two", [12], new Color(0, 200, 0)),
	])->withCustomTotalLabelColor(new Color(0, 0, 255));

	return $DIC->ui()->renderer()->render($c);
}
