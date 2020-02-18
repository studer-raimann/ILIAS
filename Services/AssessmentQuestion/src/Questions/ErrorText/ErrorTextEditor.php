<?php

namespace ILIAS\AssessmentQuestion\Questions\ErrorText;

use ILIAS\AssessmentQuestion\ilAsqHtmlPurifier;
use ILIAS\AssessmentQuestion\DomainModel\AbstractConfiguration;
use ILIAS\AssessmentQuestion\DomainModel\Question;
use ILIAS\AssessmentQuestion\DomainModel\QuestionDto;
use ILIAS\AssessmentQuestion\DomainModel\Answer\Answer;
use ILIAS\AssessmentQuestion\UserInterface\Web\Component\Editor\AbstractEditor;
use ILIAS\AssessmentQuestion\UserInterface\Web\Component\Editor\EmptyDisplayDefinition;
use ilNumberInputGUI;
use ilTemplate;
use ilTextAreaInputGUI;
use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class ErrorTextEditor
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class ErrorTextEditor extends AbstractEditor {
    const DEFAULT_TEXTSIZE_PERCENT = 100;
    
    const VAR_ERROR_TEXT = 'ete_error_text';
    const VAR_TEXT_SIZE = 'ete_text_size';
    
    /**
     * @var ErrorTextEditorConfiguration
     */
    private $configuration;
    /**
     * @var ErrorTextAnswer
     */
    private $answer;
    
    public function __construct(QuestionDto $question) {
        $this->configuration = $question->getPlayConfiguration()->getEditorConfiguration();

        parent::__construct($question);
    }
    
    /**
     * @return string
     */
    public function generateHtml() : string
    {
        $tpl = new ilTemplate("tpl.ErrorTextEditor.html", true, true, "Services/AssessmentQuestion");
        
        $tpl->setCurrentBlock('editor');
        $tpl->setVariable('ERRORTEXT_ID', $this->getPostKey());
        
        if ($this->configuration->getTextSize() !== 100) {
            $tpl->setVariable('STYLE', sprintf('style="font-size: %fem"', $this->configuration->getTextSize() / 100));
        }
        
        $tpl->setVariable('ERRORTEXT_VALUE', is_null($this->answer) ? '' : $this->answer->getPostString());
        $tpl->setVariable('ERRORTEXT', $this->generateErrorText());
        $tpl->parseCurrentBlock();
        
        return $tpl->get();
    }
    
    /**
     * @return string
     */
    private function getPostKey() : string {
        return $this->question->getId();
    }
    
    /**
     * @return string
     */
    private function generateErrorText() : string {
        preg_match_all('/\S+/', $this->configuration->getSanitizedErrorText(), $matches);
        
        $words = $matches[0];
        
        $text = '';
        
        for ($i = 0; $i < count($words); $i++) {
            $css = 'errortext_word';
            if (!is_null($this->answer) && in_array($i, $this->answer->getSelectedWordIndexes())) {
                $css .= ' selected';
            }
            $text .= '<span class="' . $css . '" data-index="' . $i . '">' . $words[$i] . '</span> ';
        }
        
        return $text;
    }
    
    /**
     * @return Answer
     */
    public function readAnswer() : AbstractValueObject
    {       
        $answers = $_POST[$this->getPostKey()];
        
        if(strlen($answers) > 0) {
            $answers = explode(',', $answers);
        
            $answers = array_map(function($answer) {
                return intval($answer);
            }, $answers);
            
            return ErrorTextAnswer::create($answers);
        }
        else {
            return ErrorTextAnswer::create();
        }
    }
    
    /**
     * @param AbstractValueObject $answer
     */
    public function setAnswer(AbstractValueObject $answer) : void
    {
        $this->answer = $answer;
    }
    
    public static function generateFields(?AbstractConfiguration $config): ?array {
        /** @var ErrorTextEditorConfiguration $config */
        global $DIC;
        
        $fields = [];
        
        $error_text = new ilTextAreaInputGUI($DIC->language()->txt('asq_label_error_text'), self::VAR_ERROR_TEXT);
        $error_text->setInfo('<input type="button" id="process_error_text" value="' . 
                                $DIC->language()->txt('asq_label_process_error_text') . 
                             '" class="btn btn-default btn-sm" /><br />' . 
                             $DIC->language()->txt('asq_description_error_text'));
        $error_text->setRequired(true);
        $fields[self::VAR_ERROR_TEXT] = $error_text;
        
        
        $text_size = new ilNumberInputGUI($DIC->language()->txt('asq_label_text_size'), self::VAR_TEXT_SIZE);
        $text_size->setSize(6);
        $text_size->setSuffix('%');
        $fields[self::VAR_TEXT_SIZE] = $text_size;
        
        if ($config !== null) {
            $error_text->setValue($config->getErrorText());
            $text_size->setValue($config->getTextSize());
        }
        else {
            $text_size->setValue(self::DEFAULT_TEXTSIZE_PERCENT);
        }
        
        return $fields;
    }
    
    /**
     * @return AbstractConfiguration|null
     */
    public static function readConfig() : ?AbstractConfiguration {
        return ErrorTextEditorConfiguration::create(
            ilAsqHtmlPurifier::getInstance()->purify($_POST[self::VAR_ERROR_TEXT]),
            intval($_POST[self::VAR_TEXT_SIZE]));
    }
    
    /**
     * @return string
     */
    static function getDisplayDefinitionClass() : string {
        return EmptyDisplayDefinition::class;
    }
    
    public static function isComplete(Question $question): bool
    {
        /** @var ErrorTextEditorConfiguration $config */
        $config = $question->getPlayConfiguration()->getEditorConfiguration();
        
        if (empty($config->getErrorText())) {
            return false;
        }
        
        return true;
    }
}