<?php
/* Copyright (c) 1998-2019 ILIAS open source, Extended GPL, see docs/LICENSE */

namespace ILIAS\AssessmentQuestion\Test;

use PHPUnit\Framework\TestCase;
use ilInitialisation;
use ilUnitUtil;
use ILIAS\AssessmentQuestion\DomainModel\QuestionDto;
use ILIAS\AssessmentQuestion\DomainModel\Answer\Answer;

/**
 * Class ilClozequestionTest
 *
 * @author      Adrian Lüthi <al@studer-raimann.ch>
 *
 * @package     Services/AssessmentQuestion
 */
abstract class QuestionTestCase extends TestCase {
    const TEST_CONTAINER = -1;
    const DONT_TEST = -1;
    
    abstract public function getQuestions() : array;
    
    abstract public function getAnswers() : array;
    
    abstract public function getExpectedScore(string $question_id, string $answer_id) : float;
    
    public function setUp() : void
    {
         include_once("./Services/PHPUnit/classes/class.ilUnitUtil.php");
         ilUnitUtil::performInitialisation();
    }
    
    public function questionAnswerProvider() : array {
        $mapping = [];
        
        foreach ($this->getQuestions() as $question_id => $question) {
            foreach ($this->getAnswers() as $answer_id => $answer) {
                $mapping[sprintf('Question %s with Answer $s', $question_id, $answer_id)] = 
                    [
                        $question, 
                        $answer, 
                        $this->getExpectedScore($question_id, $answer_id)
                    ];
            }
        }
        
        return $mapping;
    }
    
    /**
     * @param QuestionDto $question
     */
    public function testQuestionCreation() {
        global $DIC;
        
        foreach ($this->getQuestions() as $question) {
            $created = $DIC->assessment()->question()->createQuestion($question->getLegacyData()->getAnswerTypeId(), self::TEST_CONTAINER);
            $created->setData($question->getData());
            $created->setAnswerOptions($question->getAnswerOptions());
            $created->setPlayConfiguration($question->getPlayConfiguration());
            $DIC->assessment()->question()->saveQuestion($created);
            
            $loaded_created = $DIC->assessment()->question()->getQuestionByQuestionId($created->getId());
            
            $this->assertTrue($question->getData()->equals($loaded_created->getData()));
            $this->assertTrue($question->getAnswerOptions()->equals($loaded_created->getAnswerOptions()));
            $this->assertTrue($question->getPlayConfiguration()->equals($loaded_created->getPlayConfiguration()));
        }
    }
    
    /**
     * @depends testQuestionCreation
     * @dataProvider questionAnswerProvider
     * 
     * @param QuestionDto $question
     * @param Answer $answer
     * @param float $expected_score
     */
    public function testAnswers(QuestionDto $question, Answer $answer, float $expected_score) {
        global $DIC;
        
        $this->assertEquals($expected_score, $DIC->assessment()->answer()->getScore($question, $answer));
    }
    
    public static function TearDownAfterClass() : void {
        include_once("./Services/PHPUnit/classes/class.ilUnitUtil.php");
        ilUnitUtil::performInitialisation();
        
        global $DIC;
        
        $DIC->database()->manipulate(sprintf('DELETE FROM asq_qst_event_store WHERE container_id = %s;', self::TEST_CONTAINER));
    }
}