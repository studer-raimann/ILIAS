<?php
/* Copyright (c) 1998-2013 ILIAS open source, Extended GPL, see docs/LICENSE */
require_once 'Modules/Test/classes/class.ilTestQuestionSetConfig.php';

/**
 * class that manages/holds the data for a question set configuration for continues tests
 *
 * @author Björn Heyser <bheyser@databay.de>
 * @version $Id$
 *         
 * @package Modules/Test
 */
class ilTestFixedQuestionSetConfig extends ilTestQuestionSetConfig
{

    /**
     *
     * @var ilTestFixedQuestionSetQuestionList
     */
    protected $testQuestionList = null;

    /**
     *
     * @return ilTestFixedQuestionSetQuestionList
     */
    public function getTestQuestionList()
    {
        if ($this->testQuestionList === null) {
            $this->testQuestionList = new ilTestFixedQuestionSetQuestionList($this->testOBJ->getTestId());
        }

        return $this->testQuestionList;
    }

    /**
     * returns the fact wether a useable question set config exists or not
     *
     * @return boolean
     */
    public function isQuestionSetConfigured()
    {
        return $this->getTestQuestionList()->hasQuestions();
    }

    /**
     * returns the fact wether a useable question set config exists or not
     *
     * @return boolean
     */
    public function doesQuestionSetRelatedDataExist()
    {
        return $this->isQuestionSetConfigured();
    }

    /**
     * removes all question set config related data
     */
    public function removeQuestionSetRelatedData()
    {
        global $DIC; /* @var \ILIAS\DI\Container $DIC */

        $asqAuthoringService = $DIC->assessment()->questionAuthoring($this->testOBJ->getId(), $DIC->user()
            ->getId());

        $testQuestionList = $this->getTestQuestionList();

        foreach ($testQuestionList as $testQuestion) {
            $qUid = $DIC->assessment()
                ->entityIdBuilder()
                ->fromString($testQuestion->getQuestionUid());

            $asqQuestion = $asqAuthoringService->question($qUid);

            $asqQuestion->deleteQuestion();
        }

        $testQuestionList->delete();

        while ($row = $this->db->fetchAssoc($res)) {
            $this->testOBJ->removeQuestion($row["question_fi"]);
        }

        $this->testOBJ->saveCompleteStatus($this);
    }

    public function resetQuestionSetRelatedTestSettings()
    {
        // nothing to do
    }

    /**
     * removes all question set config related data for cloned/copied test
     *
     * @param ilObjTest $cloneTestOBJ
     */
    public function cloneQuestionSetRelatedData(ilObjTest $cloneTestOBJ)
    {
        global $DIC;
        $ilLog = $DIC['ilLog'];

        require_once 'Services/CopyWizard/classes/class.ilCopyWizardOptions.php';
        require_once 'Modules/TestQuestionPool/classes/class.assQuestion.php';

        $cwo = ilCopyWizardOptions::_getInstance($cloneTestOBJ->getTmpCopyWizardCopyId());

        foreach ($this->testOBJ->questions as $key => $question_id) {
            $question = assQuestion::_instanciateQuestion($question_id);
            $cloneTestOBJ->questions[$key] = $question->duplicate(true, null, null, null, $cloneTestOBJ->getId());

            $original_id = assQuestion::_getOriginalId($question_id);

            $question = assQuestion::_instanciateQuestion($cloneTestOBJ->questions[$key]);
            $question->saveToDb($original_id);

            // Save the mapping of old question id <-> new question id
            // This will be used in class.ilObjCourse::cloneDependencies to copy learning objectives
            $originalKey = $this->testOBJ->getRefId() . '_question_' . $question_id;
            $mappedKey = $cloneTestOBJ->getRefId() . '_question_' . $cloneTestOBJ->questions[$key];
            $cwo->appendMapping($originalKey, $mappedKey);
            $ilLog->write(__METHOD__ . ": Added question id mapping $originalKey <-> $mappedKey");
        }
    }

    /**
     * loads the question set config for current test from the database
     */
    public function loadFromDb()
    {
        // TODO: Implement loadFromDb() method.
    }

    /**
     * saves the question set config for current test to the database
     */
    public function saveToDb()
    {
        // TODO: Implement saveToDb() method.
    }

    /**
     *
     * @return ilTestReindexedSequencePositionMap
     */
    public function reindexQuestionOrdering()
    {
        $query = "
			SELECT question_fi, sequence FROM tst_test_question
			WHERE test_fi = %s
			ORDER BY sequence ASC
		";

        $res = $this->db->queryF($query, array(
            'integer'
        ), array(
            $this->testOBJ->getTestId()
        ));

        $sequenceIndex = 0;

        require_once 'Modules/Test/classes/class.ilTestReindexedSequencePositionMap.php';
        $reindexedSequencePositionMap = new ilTestReindexedSequencePositionMap();

        while ($row = $this->db->fetchAssoc($res)) {
            $sequenceIndex ++; // start with 1

            $reindexedSequencePositionMap->addPositionMapping($row['sequence'], $sequenceIndex);

            $this->db->update('tst_test_question', array(
                'sequence' => array(
                    'integer',
                    $sequenceIndex
                )
            ), array(
                'question_fi' => array(
                    'integer',
                    $row['question_fi']
                )
            ));
        }

        return $reindexedSequencePositionMap;
    }

    /**
     * saves the question set config for test with given id to the database
     *
     * @param
     *            $testId
     */
    public function cloneToDbForTestId($testId)
    {
        // TODO: Implement saveToDbByTestId() method.
    }

    /**
     * deletes the question set config for current test from the database
     */
    public function deleteFromDb()
    {
        // TODO: Implement deleteFromDb() method.
    }

    public function isResultTaxonomyFilterSupported()
    {
        return false;
    }

    public function registerCreatedQuestion(\ILIAS\AssessmentQuestion\DomainModel\QuestionDto $questionDto)
    {
        $testQuestion = $this->getTestQuestionList()->appendQuestion($questionDto->getQuestionIntId(), $questionDto->getId());

        if (ilObjAssessmentFolder::_enabledAssessmentLogging()) {
            global $DIC; /* @var \ILIAS\DI\Container $DIC */
            $logMsg = $DIC->language()->txtlng("assessment", "log_question_added", ilObjAssessmentFolder::_getLogLanguage());
            $logMsg .= ": {$testQuestion->getSequencePosition()}";
            $this->testOBJ->logAction($logMsg, $testQuestion->getQuestionId());
        }

        $this->testOBJ->loadQuestions();
        $this->testOBJ->saveCompleteStatus($this);
    }

    public function updateRevisionId(string $questionUid, string $questionRevisionId): void
    {
        global $DIC;

        $DIC->database()->update('tst_test_question', [
            'revision_id' => [
                'text',
                $questionRevisionId
            ]
        ], [
            'question_uid' => [
                'text',
                $questionUid
            ]
        ]);
    }
}
