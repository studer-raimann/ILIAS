<?php

namespace ILIAS\AssessmentQuestion\UserInterface\Web\Component\Editor;

use ILIAS\AssessmentQuestion\ilAsqHtmlPurifier;
use ILIAS\AssessmentQuestion\DomainModel\AbstractConfiguration;
use ILIAS\AssessmentQuestion\DomainModel\Question;
use ILIAS\AssessmentQuestion\DomainModel\QuestionDto;
use ILIAS\AssessmentQuestion\UserInterface\Web\Fields\AsqTableInput;
use ILIAS\AssessmentQuestion\UserInterface\Web\Fields\AsqTableInputFieldDefinition;
use ilFormSectionHeaderGUI;
use ilSelectInputGUI;
use ilTextAreaInputGUI;
use ilNumberInputGUI;

/**
 * Class ClozeEditor
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class ClozeEditor extends AbstractEditor {
    const VAR_CLOZE_TEXT = 'cze_text';
    const VAR_GAP_TYPE = 'cze_gap_type';
    const VAR_GAP_ITEMS = 'cze_gap_items';
    const VAR_GAP_VALUE = 'cze_gap_value';
    const VAR_GAP_UPPER = 'cze_gap_upper';
    const VAR_GAP_LOWER = 'cze_gap_lower';
    const VAR_GAP_POINTS = 'cze_gap_points';
    
    /**
     * @var ClozeEditorConfiguration
     */
    private $configuration;
    /**
     * @var array
     */
    private $answers;
    
    /**
     * @var AsqTableInput[]
     */
    private static $gap_tables;
    
    public function __construct(QuestionDto $question) {
        $this->answers = [];
        $this->configuration = $question->getPlayConfiguration()->getEditorConfiguration();
        
        parent::__construct($question);
    }
    
    public function readAnswer(): string
    {
        $this->answers = [];
        
        for ($i = 1; $i <= count($this->configuration->getGaps()); $i += 1) {
            $this->answers[$i] = ilAsqHtmlPurifier::getInstance()->purify($_POST[$this->getPostVariable($i)]);
        }
        
        return json_encode($this->answers);
    }

    public static function readConfig()
    {
        return ClozeEditorConfiguration::create(
            ilAsqHtmlPurifier::getInstance()->purify($_POST[self::VAR_CLOZE_TEXT]),
            self::readGapConfig());
    }

    /**
     * @return array
     */
    public static function readGapConfig() : array {
        $i = 1;
        $gap_configs = [];
        
        while (array_key_exists($i . self::VAR_GAP_TYPE, $_POST)) {            
            if ($_POST[$i . self::VAR_GAP_TYPE] == ClozeGapConfiguration::TYPE_DROPDOWN ||
                $_POST[$i . self::VAR_GAP_TYPE] == ClozeGapConfiguration::TYPE_TEXT) {
                $gap_configs[] = 
                    ClozeGapConfiguration::createText(
                        ilAsqHtmlPurifier::getInstance()->purify($_POST[$i . self::VAR_GAP_TYPE]), 
                        array_map(function($raw_item) {
                            return ClozeGapItem::create(
                                $raw_item[ClozeGapItem::VAR_TEXT], 
                                $raw_item[ClozeGapItem::VAR_POINTS]
                            );
                        }, self::$gap_tables[$i]->readValues()));
            }
            else if ($_POST[$i . self::VAR_GAP_TYPE] == ClozeGapConfiguration::TYPE_NUMBER) {
                $gap_configs[] =
                ClozeGapConfiguration::createNumber(
                    ilAsqHtmlPurifier::getInstance()->purify($_POST[$i . self::VAR_GAP_TYPE]), 
                    floatval($_POST[$i . self::VAR_GAP_VALUE]), 
                    floatval($_POST[$i . self::VAR_GAP_UPPER]), 
                    floatval($_POST[$i . self::VAR_GAP_LOWER]), 
                    intval($_POST[$i . self::VAR_GAP_POINTS]));
            }
            $i += 1;
        }
        
        return $gap_configs;
    }
    
    public function setAnswer(string $answer): void
    {
        $this->answers = json_decode($answer, true);
    }

    public function generateHtml(): string
    {
        $output = $this->configuration->getClozeText();
        
        for ($i = 1; $i <= count($this->configuration->getGaps()); $i += 1) {
            $gap_config = $this->configuration->getGaps()[$i - 1];
            
            if ($gap_config->getType() === ClozeGapConfiguration::TYPE_DROPDOWN) {
                $output = $this->createDropdown($i, $gap_config, $output);
            }
            else if ($gap_config->getType() === ClozeGapConfiguration::TYPE_NUMBER) {
                $output = $this->createText($i, $gap_config, $output);
            }
            else if ($gap_config->getType() === ClozeGapConfiguration::TYPE_TEXT) {
                $output = $this->createText($i, $gap_config, $output);
            }
        }
        
        return $output;
    }
    
    /**
     * @param int $index
     * @param ClozeGapConfiguration $gap_config
     * @param string $output
     * @return string
     */
    private function createDropdown(int $index, ClozeGapConfiguration $gap_config, string $output) : string{
        $name = '{' . $index . '}';
        
        $html = sprintf('<select length="20" name="%s">%s</select>',
            $this->getPostVariable($index),
            $this->createOptions($gap_config->getItems(), $index));
        
        return str_replace($name, $html, $output);
    }
    
    /**
     * @param ClozeGapItem[] $gapItems
     * @return string
     */
    private function createOptions(array $gap_items, int $index) : string {
        return implode(array_map(
            function(ClozeGapItem $gap_item) use ($index) {
                return sprintf('<option value="%1$s" %2$s>%1$s</option>', 
                               $gap_item->getText(),
                               $gap_item->getText() === $this->answers[$index] ? 'selected="selected"' : '');
            }, 
            $gap_items
        ));
    }
    
    /**
     * @param int $index
     * @param ClozeGapConfiguration $gap_config
     * @param string $output
     * @return string
     */
    private function createText(int $index, ClozeGapConfiguration $gap_config, string $output) : string {
        $name = '{' . $index . '}';
        
        $html = sprintf('<input type="text" length="20" name="%s" value="%s" />',
            $this->getPostVariable($index),
            $this->answers[$index] ?? '');
        
        return str_replace($name, $html, $output);
    }

    /**
     * @param int $index
     * @return string
     */
    private function getPostVariable(int $index) {
        return $index . $this->question->getId();
    }
    
    public static function isComplete(Question $question): bool
    {
        return true;
    }

    public static function generateFields(?AbstractConfiguration $config): ?array {
        /** @var ClozeEditorConfiguration $config */
        global $DIC;
        
        self::$gap_tables = [];
        $fields = [];
        
        $cloze_text = new ilTextAreaInputGUI($DIC->language()->txt('asq_label_cloze_text'), self::VAR_CLOZE_TEXT);
        $cloze_text->setRequired(true);
        $cloze_text->setInfo($DIC->language()->txt('asq_description_cloze_text'));
        $fields[self::VAR_CLOZE_TEXT] = $cloze_text;
        
        for ($i = 1; $i <= count($config->getGaps()); $i += 1) {
            $fields = array_merge($fields, ClozeEditor::createGapFields($i, $config->getGaps()[$i - 1]));
        }
        
        if ($config !== null) {
            $cloze_text->setValue($config->getClozeText());
        }
        else {
            $fields = array_merge($fields, ClozeEditor::createGapFields(1));
        }

        return $fields;
    }
    
    private static function createGapFields(int $index, ClozeGapConfiguration $gap = null) {
        global $DIC;

        $fields = [];
        
        $spacer = new ilFormSectionHeaderGUI();
        $spacer->setTitle('');
        $fields[] = $spacer;
        
        $gap_type = new ilSelectInputGUI($DIC->language()->txt('asq_label_gap_type'), $index . self::VAR_GAP_TYPE);
        $gap_type->setOptions([ 
            ClozeGapConfiguration::TYPE_DROPDOWN => $DIC->language()->txt('asq_label_gap_type_dropdown'),
            ClozeGapConfiguration::TYPE_TEXT => $DIC->language()->txt('asq_label_gap_type_text'),
            ClozeGapConfiguration::TYPE_NUMBER => $DIC->language()->txt('asq_label_gap_type_number')
        ]);
        $fields[$index . self::VAR_GAP_TYPE] = $gap_type;

        if (!is_null($gap)) {
            $gap_type->setValue($gap->getType());
            
            if ($gap->getType() === ClozeGapConfiguration::TYPE_DROPDOWN ||
                $gap->getType() === ClozeGapConfiguration::TYPE_TEXT) {
                $fields = array_merge($fields, self::createTextGapFields($gap, $index));        
            }
            else if ($gap->getType() === ClozeGapConfiguration::TYPE_NUMBER) {
                $fields = array_merge($fields, self::createNumberGapFields($gap, $index));
            }
        }
        
        return $fields;
    }
    
    private static function createTextGapFields(ClozeGapConfiguration $gap, int $index) {
        global $DIC;
        
        $fields = [];
        
        $items = is_null($gap) ? [] : $gap->getItemsArray();
        
        $gap_items = new AsqTableInput($DIC->language()->txt('asq_label_gap_items'), $index .self::VAR_GAP_ITEMS, $items, [
            new AsqTableInputFieldDefinition(
                $DIC->language()->txt('asq_header_value'),
                AsqTableInputFieldDefinition::TYPE_TEXT,
                ClozeGapItem::VAR_TEXT),
            new AsqTableInputFieldDefinition(
                $DIC->language()->txt('asq_header_points'),
                AsqTableInputFieldDefinition::TYPE_TEXT,
                ClozeGapItem::VAR_POINTS)
        ]);
        $gap_items->setRequired(true);
        self::$gap_tables[$index] = $gap_items;
        $fields[$index .self::VAR_GAP_ITEMS] = $gap_items;
        
        return $fields;
    }
    
    private static function createNumberGapFields(ClozeGapConfiguration $gap, int $index) {
        global $DIC;
        
        $fields = [];
        
        $value = new ilNumberInputGUI($DIC->language()->txt('asq_label_gap_value'), $index . self::VAR_GAP_VALUE);
        $value->setRequired(true);
        $value->allowDecimals(true);
        $value->setValue($gap->getValue());
        $fields[$index . self::VAR_GAP_VALUE] = $value;
        
        $upper = new ilNumberInputGUI($DIC->language()->txt('asq_label_gap_upper'), $index . self::VAR_GAP_UPPER);
        $upper->setRequired(true);
        $upper->allowDecimals(true);
        $upper->setValue($gap->getUpper());
        $fields[$index . self::VAR_GAP_UPPER]= $upper;
        
        $lower = new ilNumberInputGUI($DIC->language()->txt('asq_label_gap_lower'), $index . self::VAR_GAP_LOWER);
        $lower->setRequired(true);
        $lower->allowDecimals(true);
        $lower->setValue($gap->getLower());
        $fields[$index . self::VAR_GAP_LOWER] = $lower;
        
        $points = new ilNumberInputGUI($DIC->language()->txt('asq_label_gap_points'), $index . self::VAR_GAP_POINTS);
        $points->setRequired(true);
        $points->setValue($gap->getPoints());
        $fields[$index . self::VAR_GAP_POINTS] = $points;
        
        return $fields;
    }
    
    /**
     * @return string
     */
    static function getDisplayDefinitionClass() : string {
        return EmptyDisplayDefinition::class;
    }
}