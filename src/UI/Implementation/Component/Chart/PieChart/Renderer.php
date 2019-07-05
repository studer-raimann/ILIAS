<?php

namespace ILIAS\UI\Implementation\Component\Chart\PieChart;

use ILIAS\Data\Color;
use ILIAS\UI\Component\Chart\PieChart\PieChart as PieChartInterface;
use ILIAS\UI\Component\Component;
use ILIAS\UI\Implementation\Render\AbstractComponentRenderer;
use ILIAS\UI\Renderer as RendererInterface;

/**
 * Class Renderer
 *
 * @package ILIAS\UI\Implementation\Component\Chart\PieChart
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Renderer extends AbstractComponentRenderer {

	/**
	 * @inheritDoc
	 */
	protected function getComponentInterfaceName(): array {
		return [ PieChartInterface::class ];
	}


	/**
	 * @inheritDoc
	 */
	public function render(Component $component, RendererInterface $default_renderer) {
		$this->checkComponent($component);

		return $this->renderStandard($component, $default_renderer);
	}


	/**
	 * @param PieChartInterface $component
	 * @param RendererInterface $default_renderer
	 *
	 * @return string
	 */
	protected function renderStandard(PieChartInterface $component, RendererInterface $default_renderer): string {
		$tpl = $this->getTemplate("tpl.piechart.html", true, true);

		foreach ($component->getChartItems() as $section) {
			$tpl->setCurrentBlock("section");
			$tpl->setVariable("STROKE_LENGTH", $section->getStrokeLength());
			$tpl->setVariable("OFFSET", $section->getOffset());
			$tpl->setVariable("SECTION_COLOR", $section->getColor()->asHex());
			$tpl->parseCurrentBlock();
		}

		if ($component->isShowLegend()) {
			foreach ($component->getLegend()->getEntries() as $entry) {
				$tpl->setCurrentBlock("legend");

				$customLegendTextColor = $component->getCustomLegendTextColor();

				if (is_null($component->getCustomLegendTextColor())) {
					$customLegendTextColor = $entry->getColor();
				}

				$tpl->setVariable("LEGEND_FONT_COLOR", $customLegendTextColor->asHex());
				$tpl->setVariable("SECTION_COLOR", $entry->getRectColor()->asHex());
				$tpl->setVariable("LEGEND_RECT_X_PERCENTAGE", $entry->getRectPoint()->getX());
				$tpl->setVariable("LEGEND_RECT_Y_PERCENTAGE", $entry->getRectPoint()->getY());
				$tpl->setVariable("LEGEND_X_PERCENTAGE", $entry->getPoint()->getX());
				$tpl->setVariable("LEGEND_Y_PERCENTAGE", $entry->getPoint()->getY());
				$tpl->setVariable("LEGEND_FONT_SIZE", $entry->getFontSize());
				$tpl->setVariable("RECT_SIZE", $component->getLegend()->getRectSize());
				$tpl->setVariable("SECTION_NAME", $entry->getText());
				$tpl->parseCurrentBlock();
			}
		}

		foreach ($component->getChartItems() as $section) {
			$tpl->setCurrentBlock("section_text");

			$customSectionLabelColor = $component->getCustomSectionLabelColor();

			if (is_null($component->getCustomSectionLabelColor())) {
				$customSectionLabelColor = $section->getSectionLabel()->getColor();
			}

			$tpl->setVariable("TEXT_COLOR", $customSectionLabelColor->asHex());
			$tpl->setVariable("VALUE_X_PERCENTAGE", $section->getSectionLabel()->getPoint()->getX());
			$tpl->setVariable("VALUE_Y_PERCENTAGE", $section->getSectionLabel()->getPoint()->getY());
			$tpl->setVariable("SECTION_VALUE", $section->getSectionLabel()->getText());
			$tpl->setVariable("VALUE_FONT_SIZE", $section->getSectionLabel()->getFontSize());
			$tpl->parseCurrentBlock();
		}

		$tpl->setCurrentBlock("total");
		$total_value = $component->getCustomTotalValue();
		$total_color = $component->getCustomTotalLabelColor();

		if (is_null($total_value)) {
			$total_value = $component->getTotalValue();
		}

		if (is_null($total_color)) {
			$total_color = new Color(0, 0, 0);
		}

		$tpl->setVariable("TOTAL_VALUE", round($total_value, 2));
		$tpl->setVariable("TOTAL_COLOR", $total_color->asHex());
		$tpl->parseCurrentBlock();

		return $tpl->get();
	}
}
