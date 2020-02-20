<?php

namespace ILIAS\AssessmentQuestion\Questions\Ordering;

use ILIAS\AssessmentQuestion\DomainModel\AbstractConfiguration;
use ILIAS\AssessmentQuestion\DomainModel\Question;
use ILIAS\AssessmentQuestion\DomainModel\QuestionDto;
use ILIAS\AssessmentQuestion\UserInterface\Web\Component\Editor\AbstractEditor;
use ILIAS\AssessmentQuestion\UserInterface\Web\Component\Editor\ImageAndTextDisplayDefinition;
use ilNumberInputGUI;
use ilSelectInputGUI;
use ilTemplate;
use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class OrderingEditor
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class OrderingEditor extends AbstractEditor {
    const VAR_VERTICAL = "oe_vertical";
    const VAR_MINIMUM_SIZE = "oe_minimum_size";
    const VERTICAL = "vertical";
    const HORICONTAL = "horicontal";
    
    /**
     * @var OrderingEditorConfiguration
     */
    private $configuration;
    /**
     * @var array
     */
    private $display_ids;
    
    public function __construct(QuestionDto $question) {      
        $this->configuration = $question->getPlayConfiguration()->getEditorConfiguration();
        
        $this->calculateDisplayIds($question->getAnswerOptions()->getOptions());
        
        parent::__construct($question);
    }
    
    private function calculateDisplayIds($options) {
        $this->display_ids = array_map(function($item) {
            return $item->getDisplayDefinition()->getText();
        }, $options);
        
        sort($this->display_ids);
    }

    /**
     * @return string
     */
    public function generateHtml() : string
    {
        $tpl = new ilTemplate("tpl.OrderingEditor.html", true, true, "Services/AssessmentQuestion");

        if (empty($this->answer)) {
            $items = $this->question->getAnswerOptions()->getOptions();
            shuffle($items);
        }
        else {
            $items = $this->orderItemsByAnswer();
        }

        foreach ($items as $item) {
            $tpl->setCurrentBlock('item');
            $tpl->setVariable('OPTION_ID', array_search($item->getDisplayDefinition()->getText(), $this->display_ids));
            $tpl->setVariable('ITEM_TEXT', $item->getDisplayDefinition()->getText());
            
            if (!empty($this->configuration->getMinimumSize())) {
                $tpl->setVariable('HEIGHT', sprintf(' style="height: %spx" ', $this->configuration->getMinimumSize()));
            }
            
            $tpl->parseCurrentBlock();
        }

        $tpl->setCurrentBlock('editor');
        
        if (!$this->configuration->isVertical()) {
            $tpl->setVariable('ADD_CLASS', 'horizontal');
        }
        
        $tpl->setVariable('POST_NAME', $this->question->getId());
        $tpl->setVariable('ANSWER', $this->getAnswerString($items));
        $tpl->parseCurrentBlock();

        return $tpl->get();
    }
    
    private function getAnswerString($items) {
        return implode(',', array_map(function($item) {
            return array_search($item->getDisplayDefinition()->getText(), $this->display_ids);
        }, $items));
    }

    private function orderItemsByAnswer() : array {
        $answers = $this->question->getAnswerOptions()->getOptions();

        $items = [];

        foreach ($this->answer->getSelectedOrder() as $index) {
            $items[] = $answers[$index - 1];
        }

        return $items;
    }

    public function readAnswer(): AbstractValueObject
    {
        return OrderingAnswer::create(array_map(function($display_id) {
            foreach($this->question->getAnswerOptions()->getOptions() as $option) {
                if ($this->display_ids[$display_id] === $option->getDisplayDefinition()->getText()) {
                    return $option->getOptionId();
                }
            }
        }, explode(',', $_POST[$this->question->getId()])));
    }

    public static function generateFields(?AbstractConfiguration $config): ?array {
        /** @var OrderingEditorConfiguration $config */
        global $DIC;
        
        $fields = [];
        
        $is_vertical = new ilSelectInputGUI($DIC->language()->txt('asq_label_is_vertical'), self::VAR_VERTICAL);
        $is_vertical->setOptions([
            self::VERTICAL => $DIC->language()->txt('asq_label_vertical'),
            self::HORICONTAL => $DIC->language()->txt('asq_label_horicontal')
        ]);
        $fields[self::VAR_VERTICAL] = $is_vertical;
        
        $minimum_size = new ilNumberInputGUI($DIC->language()->txt('asq_label_min_size'), self::VAR_MINIMUM_SIZE);
        $minimum_size->setInfo($DIC->language()->txt('asq_description_min_size'));
        $minimum_size->setSize(6);
        $fields[self::VAR_MINIMUM_SIZE] = $minimum_size;
        
        if ($config !== null) {
            $minimum_size->setValue($config->getMinimumSize());
            $is_vertical->setValue($config->isVertical() ? self::VERTICAL : self::HORICONTAL);
        }
        else {
            $is_vertical->setValue(self::VERTICAL);
        }
        
        return $fields;
    }
    
    public static function readConfig()
    {
        return OrderingEditorConfiguration::create(
            $_POST[self::VAR_VERTICAL] === self::VERTICAL, 
            !empty($_POST[self::VAR_MINIMUM_SIZE]) ? intval($_POST[self::VAR_MINIMUM_SIZE]) : null);
    }
    
    /**
     * @return string
     */
    static function getDisplayDefinitionClass() : string {
        return ImageAndTextDisplayDefinition::class;
    }
    
    public static function isComplete(Question $question): bool
    {
        foreach ($question->getAnswerOptions()->getOptions() as $option) {
            /** @var ImageAndTextDisplayDefinition $option_config */
            $option_config = $option->getDisplayDefinition();
            
            if (empty($option_config->getText()))
            {
                return false;
            }
        }
        
        return true;
    }
}