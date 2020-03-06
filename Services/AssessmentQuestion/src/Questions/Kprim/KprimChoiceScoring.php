<?php

namespace ILIAS\AssessmentQuestion\Questions\Kprim;

use ILIAS\AssessmentQuestion\DomainModel\AbstractConfiguration;
use ILIAS\AssessmentQuestion\DomainModel\Question;
use ILIAS\AssessmentQuestion\DomainModel\Answer\Answer;
use ILIAS\AssessmentQuestion\DomainModel\Answer\Option\AnswerOptions;
use ILIAS\AssessmentQuestion\DomainModel\Scoring\AbstractScoring;
use ilNumberInputGUI;

/**
 * Class KprimChoiceScoring
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class KprimChoiceScoring extends AbstractScoring {
    const VAR_POINTS = 'kcs_points';
    const VAR_HALF_POINTS = 'kcs_half_points_at';
    
    /**
     * @param KprimChoiceAnswer $answer
     * @return float
     */
    function score(Answer $answer) : float {
        $count = 0;
        foreach ($this->question->getAnswerOptions()->getOptions() as $option) {
            /** @var KprimChoiceScoringDefinition $scoring_definition */
            $scoring_definition = $option->getScoringDefinition();
            $current_answer = $answer->getAnswerForId($option->getOptionId());
            if (!is_null($current_answer)) {
                if ($current_answer == true && $scoring_definition->isCorrectValue() ||
                    $current_answer == false && !$scoring_definition->isCorrectValue()) {
                    $count += 1;
                }
            }
        }
        
        /** @var KprimChoiceScoringConfiguration $scoring_conf */
        $scoring_conf = $this->question->getPlayConfiguration()->getScoringConfiguration();

        if ($count === count($this->question->getAnswerOptions()->getOptions())) {
            return $scoring_conf->getPoints();
        } 
        else if (!is_null($scoring_conf->getHalfPointsAt()) &&
                 $count >= $scoring_conf->getHalfPointsAt()) {
            return floor($scoring_conf->getPoints() / 2);
        }
        else {
            return 0;
        }
    }
    
    protected function calculateMaxScore()
    {
        $this->max_score = $this->question->getPlayConfiguration()->getScoringConfiguration()->getPoints();
    }
    
    public function getBestAnswer(): Answer
    {
        $answers = [];
        
        foreach ($this->question->getAnswerOptions()->getOptions() as $option) {
            /** @var KprimChoiceScoringDefinition $scoring_definition */
            $scoring_definition = $option->getScoringDefinition();
            
            if ($scoring_definition->isCorrectValue()) {
                $answers[$option->getOptionId()] = true;
            }
            else {
                $answers[$option->getOptionId()] = false;
            }
        }
        
        return KprimChoiceAnswer::create($answers);
    }
    
    /**
     * @return array|null
     */
    public static function generateFields(?AbstractConfiguration $config, AnswerOptions $options = null): ?array {
        global $DIC;
        
        $fields = [];
        
        $points = new ilNumberInputGUI($DIC->language()->txt('asq_label_points'), self::VAR_POINTS);
        $points->setRequired(true);
        $points->setSize(2);
        $fields[self::VAR_POINTS] = $points;
        
        $half_points_at = new ilNumberInputGUI($DIC->language()->txt('asq_label_half_points'), self::VAR_HALF_POINTS);
        $half_points_at->setInfo($DIC->language()->txt('asq_description_half_points'));
        $half_points_at->setSize(2);
        $fields[self::VAR_HALF_POINTS] = $half_points_at;
        
        if ($config !== null) {
            $points->setValue($config->getPoints());
            $half_points_at->setValue($config->getHalfPointsAt());
        }
        
        return $fields;
    }
    
    /**
     * @return ?AbstractConfiguration|null
     */
    public static function readConfig() : ?AbstractConfiguration {        
        return KprimChoiceScoringConfiguration::create(
            floatval($_POST[self::VAR_POINTS]),
            array_key_exists(self::VAR_HALF_POINTS, $_POST) ? intval($_POST[self::VAR_HALF_POINTS]) : null);
    }
    
    public static function isComplete(Question $question): bool
    {
        /** @var KprimChoiceScoringConfiguration $config */
        $config = $question->getPlayConfiguration()->getScoringConfiguration();
        
        if (empty($config->getPoints())) {
            return false;
        }
        
        if (count($question->getAnswerOptions()->getOptions()) < 1) {
            return false;
        }
        
        foreach ($question->getAnswerOptions()->getOptions() as $option) {
            /** @var KprimChoiceScoringDefinition $option_config */
            $option_config = $option->getScoringDefinition();
            
            if (empty($option_config->isCorrectValue()))
            {
                return false;
            }
        }
        
        return true;
    }
}