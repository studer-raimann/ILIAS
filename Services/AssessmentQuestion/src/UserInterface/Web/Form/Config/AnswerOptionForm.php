<?php

namespace ILIAS\AssessmentQuestion\UserInterface\Web\Form\Config;

use Exception;
use ILIAS\AssessmentQuestion\DomainModel\Answer\Option\AnswerOption;
use ilImageFileInputGUI;
use ilNumberInputGUI;
use ilTemplate;
use ilTextInputGUI;
use ilHiddenInputGUI;
use ILIAS\AssessmentQuestion\UserInterface\Web\Form\QuestionFormGUI;
use ilRadioGroupInputGUI;
use ilRadioOption;

/**
 * Class AnswerOptionForm
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class AnswerOptionForm extends ilTextInputGUI {

	const COUNT_POST_VAR = 'option_count';

	/**
	 * @var array
	 */
	private $definitions;
	/**
	 * @var array
	 */
	private $options;

	public function __construct(string $title, array $definitions, array $options) {
		parent::__construct($title);
		$this->definitions = $definitions;

		//add empty row if there are no answers
		if (sizeof($options) === 0) {
			$this->options[] = null;
		} else {
			$this->options = $options;
		}
	}

	/**
	 * @param string $a_mode
	 *
	 * @return string
	 * @throws \ilTemplateException
	 */
	public function render($a_mode = '') {
		$tpl = new ilTemplate("tpl.AnswerOptionTable.html", true, true, "Services/AssessmentQuestion");

		/** @var AnswerOptionFormFieldDefinition $definition */
		foreach ($this->definitions as $definition) {
			$tpl->setCurrentBlock('header_entry');
			$tpl->setVariable('HEADER_TEXT', $definition->getHeader());
			$tpl->parseCurrentBlock();
		}

		$tpl->setCurrentBlock('commands');
		$tpl->setVariable('COMMANDS_TEXT', 'Actions');
		$tpl->parseCurrentBlock();

		$row_id = 1;

		/** @var AnswerOption $option */
		foreach ($this->options as $option) {
			$def_pos = 0;

			/** @var AnswerOptionFormFieldDefinition $definition */
			foreach ($this->definitions as $definition) {
				$tpl->setCurrentBlock('body_entry');
				$tpl->setVariable('ENTRY_CLASS', ''); //TODO get class by type
				$tpl->setVariable('ENTRY', $this->generateField($definition, $row_id, $option !== null ? $option->rawValues()[$definition->getPostVar()] : null));
				$tpl->parseCurrentBlock();

				$def_pos += 1;
			}

			$tpl->setCurrentBlock('row');
			$tpl->setVariable('ID', $row_id);
			$tpl->parseCurrentBlock();

			$row_id += 1;
		}

		$tpl->setCurrentBlock('count');
		$tpl->setVariable('COUNT_POST_VAR', self::COUNT_POST_VAR);
		$tpl->setVariable('COUNT', sizeof($this->options));
		$tpl->parseCurrentBlock();


		return $tpl->get();
	}


	/**
	 * @param AnswerOptionFormFieldDefinition $definition
	 * @param int                             $row_id
	 * @param                                 $value
	 *
	 * @return string
	 * @throws Exception
	 */
	private function generateField(AnswerOptionFormFieldDefinition $definition, int $row_id, $value)
	{
        switch ($definition->getType()) {
            case AnswerOptionFormFieldDefinition::TYPE_TEXT:
	           return $this->generateTextField($row_id . $definition->getPostVar(), $value);
               break;
            case AnswerOptionFormFieldDefinition::TYPE_IMAGE:
                return $this->generateImageField($row_id . $definition->getPostVar(), $value);
                break;
			case AnswerOptionFormFieldDefinition::TYPE_NUMBER:
				return $this->generateNumberField($row_id . $definition->getPostVar(), $value);
				break;
			case AnswerOptionFormFieldDefinition::TYPE_RADIO;
			    return $this->generateRadioField($row_id . $definition->getPostVar(), $value, $definition->getOptions());
			    break;
			default:
				throw new Exception('Please implement all fieldtypes you define');
				break;
		}
	}


	/**
	 * @param string $post_var
	 * @param        $value
	 *
	 * @return ilTextInputGUI
	 */
	private function generateTextField(string $post_var, $value) {
		$field = new ilTextInputGUI('', $post_var);
		if ($value !== null) {
			$field->setValue($value);
		}
		return $field->render();
	}

	/**
	 * @param string $post_var
	 * @param        $value
	 *
	 * @return ilImageFileInputGUI
	 */
	private function generateImageField(string $post_var, $value) {
		$field = new ilImageFileInputGUI('', $post_var);
		if ($value !== null) {
			$field->setValue($value);
		}
		$hidden = '<input type="hidden" name="' . $post_var . QuestionFormGUI::IMG_PATH_SUFFIX . '" value="' . $value . '" />';
		return $field->render() . $hidden;
	}

	/**
	 * @param string $post_var
	 * @param        $value
	 *
	 * @return ilNumberInputGUI
	 */
	private function generateNumberField(string $post_var, $value) {
		$field = new ilNumberInputGUI('', $post_var);
		if ($value !== null) {
			$field->setValue($value);
		}
		return $field->render();
	}
	
	private function generateRadioField(string $post_var, $value, $options) {
	    $field = new ilRadioGroupInputGUI('', $post_var);
	    $field->setValue($value);
	    
	    foreach ($options as $key=>$value)
	    {
    	    $option = new ilRadioOption($key, $value);
    	    $field->addOption($option);	        
	    }
	    return $field->render();
	}
}