<?php

namespace ILIAS\AssessmentQuestion\Questions\Cloze;

use ILIAS\AssessmentQuestion\DomainModel\QuestionDto;
use ILIAS\AssessmentQuestion\DomainModel\QuestionLegacyData;
use ILIAS\AssessmentQuestion\DomainModel\QuestionPlayConfiguration;
use ILIAS\AssessmentQuestion\DomainModel\Scoring\TextScoring;
include_once('QuestionTestCase.php');
use ILIAS\AssessmentQuestion\Test\QuestionTestCase;
use ILIAS\AssessmentQuestion\UserInterface\Web\AsqGUIElementFactory;

/* Copyright (c) 1998-2019 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * Class ilClozequestionTest
 *
 * @author      Adrian Lüthi <al@studer-raimann.ch>
 *
 * @package     Services/AssessmentQuestion
 */
class ClozeQuestionTest extends QuestionTestCase {
    public function getQuestions(): array
    {
        $question = new QuestionDto();
        $question->setLegacyData(QuestionLegacyData::create(AsqGUIElementFactory::TYPE_CLOZE, null));
        $question->setPlayConfiguration(QuestionPlayConfiguration::create(
            ClozeEditorConfiguration::create('', [
                NumericGapConfiguration::Create(2, 2.1, 1.9, 1),
                SelectGapConfiguration::Create([
                    ClozeGapItem::create('correct', 0),
                    ClozeGapItem::create('wrong', 2),
                ]),
                TextGapConfiguration::Create([
                    ClozeGapItem::create('correct answer', 4)
                ], 0, TextScoring::TM_CASE_INSENSITIVE)
            ]),
            ClozeScoringConfiguration::create()
        ));
        return [
            'Question' => $question
        ];
    }

    public function getAnswers(): array
    {
        return [
            'correct' => ClozeAnswer::create([
                1 => 2,
                2 => 'correct',
                3 => 'correct answer'
            ]),
            'num wrong' => ClozeAnswer::create([
                1 => 66,
                2 => 'correct',
                3 => 'correct answer'
            ]),
            'select wrong' => ClozeAnswer::create([
                1 => 2,
                2 => 'wrong',
                3 => 'correct answer'
            ]),
            'text wrong' => ClozeAnswer::create([
                1 => 2,
                2 => 'correct',
                3 => 'wrong answer'
            ]),
            'wrong' => ClozeAnswer::create([
                1 => 66,
                2 => 'wrong',
                3 => 'wrong answer'
            ])
        ];
    }

    public function getExpectedScore(string $question_id, string $answer_id): float
    {
        $map = [
            'Question' => [
                'correct' => 7,
                'num wrong' => 6,
                'select wrong' => 5,
                'text wrong' => 3,
                'wrong' => 0
            ]
        ];
        
        return $map[$question_id][$answer_id];
    }
}