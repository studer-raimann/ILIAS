<?php

namespace ILIAS\AssessmentQuestion\Play\Editor;

use ilCheckboxInputGUI;
use ilNumberInputGUI;
use JsonSerializable;
use stdClass;

/**
 * Class MultipleChoiceEditor
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class MultipleChoiceEditor extends AbstractEditor {
	const VAR_MCE_SHUFFLE = 'shuffle';
	const VAR_MCE_MAX_ANSWERS = 'max_answers';
	const VAR_MCE_THUMB_SIZE = 'thumbsize';

	/**
	 * @return string
	 */
	public function generateHtml(): string {
		// TODO: Implement generateHtml() method.
	}

	public static function generateFields(?JsonSerializable $config): ?array {
		$fields = [];

		$shuffle = new ilCheckboxInputGUI('shuffle', self::VAR_MCE_SHUFFLE);
		$shuffle->setValue(1);
		$fields[] = $shuffle;

		$max_answers = new ilNumberInputGUI('max_answers', self::VAR_MCE_MAX_ANSWERS);
		$fields[] = $max_answers;

		$thumb_size = new ilNumberInputGUI('thumb size', self::VAR_MCE_THUMB_SIZE);
		$fields[] = $thumb_size;

		if ($config !== null) {
			$shuffle->setChecked($config->isShuffleAnswers());
			$max_answers->setValue($config->getMaxAnswers());
			$thumb_size->setValue($config->getThumbnailSize());
		}

		return $fields;
	}

	/**
	 * @return JsonSerializable|null
	 */
	public static function readConfig() : ?JsonSerializable {
		return new MultipleChoiceEditorConfiguration(
			filter_var($_POST[self::VAR_MCE_SHUFFLE], FILTER_VALIDATE_BOOLEAN),
			$_POST[self::VAR_MCE_MAX_ANSWERS],
			$_POST[self::VAR_MCE_THUMB_SIZE]
		);
	}

	/**
	 * @param stdClass $input
	 *
	 * @return JsonSerializable|null
	 */
	public static function deserialize(stdClass $input) : ?JsonSerializable {
		return new MultipleChoiceEditorConfiguration(
			$input->shuffle_answers,
			$input->max_answers,
			$input->thumbnail_size
		);
	}
}