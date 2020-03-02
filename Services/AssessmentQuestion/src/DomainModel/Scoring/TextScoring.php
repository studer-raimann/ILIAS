<?php

namespace ILIAS\AssessmentQuestion\DomainModel\Scoring;

use ilSelectInputGUI;

/**
 * Class TextScoring
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class TextScoring {
    const TM_CASE_INSENSITIVE = 1;
    const TM_CASE_SENSITIVE = 2;
    const TM_LEVENSHTEIN_1 = 3;
    const TM_LEVENSHTEIN_2 = 4;
    const TM_LEVENSHTEIN_3 = 5;
    const TM_LEVENSHTEIN_4 = 6;
    const TM_LEVENSHTEIN_5 = 7;
    
    /**
     * @param string $postvar
     * @return ilSelectInputGUI
     */
    public static function getScoringTypeSelectionField(string $postvar) : ilSelectInputGUI {
        global $DIC;
        
        $text_matching = new ilSelectInputGUI($DIC->language()->txt('asq_label_text_matching'), $postvar);
        $text_matching->setOptions(
            [self::TM_CASE_INSENSITIVE => $DIC->language()->txt('asq_option_case_insensitive'),
                self::TM_CASE_SENSITIVE => $DIC->language()->txt('asq_option_case_sensitive'),
                self::TM_LEVENSHTEIN_1 => $DIC->language()->txt('asq_option_levenshtein_1'),
                self::TM_LEVENSHTEIN_2 => $DIC->language()->txt('asq_option_levenshtein_2'),
                self::TM_LEVENSHTEIN_3 => $DIC->language()->txt('asq_option_levenshtein_3'),
                self::TM_LEVENSHTEIN_4 => $DIC->language()->txt('asq_option_levenshtein_4'),
                self::TM_LEVENSHTEIN_5 => $DIC->language()->txt('asq_option_levenshtein_5')]);
        return $text_matching;
    }
    
    /**
     * @param string $a
     * @param string $b
     * @param int $matching_type
     * @return bool
     */
    public static function isMatch(string $a, string $b, int $matching_type) : bool {
        switch ($matching_type) {
            case self::TM_CASE_INSENSITIVE:
                return (strtoupper($a) === strtoupper($b));
            case self::TM_CASE_SENSITIVE:
                return $a === $b;
            case self::TM_LEVENSHTEIN_1:
            case self::TM_LEVENSHTEIN_2:
            case self::TM_LEVENSHTEIN_3:
            case self::TM_LEVENSHTEIN_4:
            case self::TM_LEVENSHTEIN_5:
                return levenshtein($a, $b) < $matching_type - 1;
        }
    }
}