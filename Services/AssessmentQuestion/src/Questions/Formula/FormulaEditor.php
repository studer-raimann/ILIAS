<?php

namespace ILIAS\AssessmentQuestion\Questions\Formula;

use ILIAS\AssessmentQuestion\ilAsqHtmlPurifier;
use ILIAS\AssessmentQuestion\DomainModel\Question;
use ILIAS\AssessmentQuestion\DomainModel\QuestionDto;
use ILIAS\AssessmentQuestion\UserInterface\Web\Component\Editor\AbstractEditor;
use ILIAS\AssessmentQuestion\UserInterface\Web\Component\Editor\EmptyDisplayDefinition;
use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class FormulaEditor
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class FormulaEditor extends AbstractEditor {
    const VAR_UNIT = 'fe_unit';
    
    /**
     * @var FormulaScoringConfiguration
     */
    private $configuration;
    /**
     * @var AbstractValueObject
     */
    private $answers;
    
    public function __construct(QuestionDto $question) {      
        $this->configuration = $question->getPlayConfiguration()->getScoringConfiguration();
        
        parent::__construct($question);
    }
    
    public function readAnswer(): AbstractValueObject
    {
        $answers = [];
        $index = 1;
        $continue = true;
        while ($continue) {
            $continue = false;

            $continue |= $this->processVar('$v' . $index, $answers);
            $continue |= $this->processVar('$r' . $index, $answers);            
            $index += 1;
        }
        
        return FormulaAnswer::create($answers);
    }
    
    private function processVar($name, &$answers) : bool {
        $postname = $this->getPostVariable($name);
        $unit_postname = $this->getUnitPostVariable($name);
        
        if (array_key_exists($postname, $_POST)) {
            $answers[$name] = ilAsqHtmlPurifier::getInstance()->purify($_POST[$postname]);
            
            if (array_key_exists($unit_postname, $_POST)) {
                $answers[$name . self::VAR_UNIT] = ilAsqHtmlPurifier::getInstance()->purify($_POST[$unit_postname]);
            }
            
            return true;
        }
        
        return false;
    }

    public static function readConfig()
    {
        return FormulaEditorConfiguration::create();
    }

    public function setAnswer(AbstractValueObject $answer): void
    {
        $this->answers = $answer;
    }

    public function generateHtml(): string
    {
        $output = $this->question->getData()->getQuestionText();
        
        $resindex = 1;
        foreach ($this->question->getAnswerOptions()->getOptions() as $option) {
            $output = $this->createResult($resindex, $output, $this->question->getPlayConfiguration()->getScoringConfiguration()->getUnits());
            $resindex += 1;
        }
        
        $varindex = 1;
        foreach ($this->configuration->getVariables() as $variable) {
                $output = $this->createVariable($varindex, $output, $variable);
                $varindex += 1;
        }
        
        return $output;
    }
    
    private function createResult(int $index, string $output, string $units) :string {
        $name = '$r' . $index;

        $html = sprintf('<input type="text" length="20" name="%s" value="%s" />%s', $this->getPostVariable($name), !is_null($this->answers) ? $this->answers->getValues()[$name] : '', ! empty($units) ? $this->createUnitSelection($units, $name) : '');

        return str_replace($name, $html, $output);
    }

    private function createUnitSelection(string $units, string $name) {
        return sprintf('<select name="%s">%s</select>',
                       $this->getUnitPostVariable($name),
                       implode(array_map(function($unit) use ($name) {
                           return sprintf('<option value="%1$s" %2$s>%1$s</option>', 
                               $unit,
                               $this->answers->getValues()[$name . self::VAR_UNIT] === $unit ? 'selected="selected"': '');
                       }, explode(',', $units))));
    }
    
    private function createVariable(int $index, string $output, FormulaScoringVariable $def) :string {
        $name = '$v' . $index;
        
        $html = sprintf('<input type="hidden" name="%1$s" value="%2$s" />%2$s %3$s',
            $this->getPostVariable($name),
            !is_null($this->answers) ? $this->answers->getValues()[$name] : $this->generateVariableValue($def),
            $def->getUnit());
        
        return str_replace($name, $html, $output);
    }
    
    private function generateVariableValue(FormulaScoringVariable $def) : string {
        $exp = 10 ** $this->configuration->getPrecision();
        
        $min = $def->getMin() * $exp;
        $max = $def->getMax() * $exp;
        $number = mt_rand($min, $max);
        
        if (!is_null($def->getMultipleOf())) {
            $mult_of = $def->getMultipleOf() * $exp;
            
            $number -= $number % $mult_of;
            
            if ($number < $min) {
                $number += $mult_of;
            }
        }
        
        $number /= $exp;
        
        return sprintf('%.' . $this->configuration->getPrecision() . 'F', $number);
    }
    
    private function getPostVariable(string $name) {
        return $name . $this->question->getId();
    }
    
    private function getUnitPostVariable(string $name) {
        return $name . $this->question->getId() . self::VAR_UNIT;
    }
    
    /**
     * @return string
     */
    static function getDisplayDefinitionClass() : string {
        return EmptyDisplayDefinition::class;
    }
    
    public static function isComplete(Question $question): bool
    {
        /** @var FormulaEditorConfiguration $config */
        $config = $question->getPlayConfiguration()->getEditorConfiguration();
        
        if (false) {
            return false;
        }
        
        return true;
    }
}