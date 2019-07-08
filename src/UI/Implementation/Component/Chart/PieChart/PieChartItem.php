<?php

namespace ILIAS\UI\Implementation\Component\Chart\PieChart;

use ILIAS\Data\Color;
use ILIAS\UI\Component\Chart\PieChart\PieChartItem as PieChartItemInterface;
use ILIAS\UI\Implementation\Component\ComponentHelper;
use InvalidArgumentException;

/**
 * Class PieChartItem
 *
 * @package ILIAS\UI\Implementation\Component\Chart\PieChart
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class PieChartItem implements PieChartItemInterface {

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
	 * @var Color
	 */
	protected $color;
	/**
	 * @var Color
	 */
	protected $textColor;


	/**
	 * PieChartItem constructor
	 *
	 * @param string     $name
	 * @param float[]    $values
	 * @param Color      $color
	 * @param Color|null $textColor
	 */
	public function __construct(string $name, array $values, Color $color, ?Color $textColor = null) {
		if (strlen($name) > self::MAX_TITLE_CHARS) {
			throw new InvalidArgumentException(self::ERR_TOO_MANY_CHARS);
		}

		if (count($values) > self::MAX_VALUES) {
			throw new InvalidArgumentException(self::ERR_TOO_MANY_VALUES);
		}

		$this->name = $name;
		$this->values = $values;
		$this->color = $color;

		if (!is_null($textColor)) {
			$this->checkArgInstanceOf("textColor", $textColor, Color::class);
			$this->textColor = $textColor;
		} else {
			$this->textColor = new Color(0, 0, 0);
		}
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
	public function getColor(): Color {
		return $this->color;
	}


	/**
	 * @inheritDoc
	 */
	public function getTextColor(): Color {
		return $this->textColor;
	}


	/**
	 * @inheritDoc
	 */
	public function getValues(): array {
		return $this->values;
	}
}
