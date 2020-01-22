<#5524>
<?php
$ilCtrlStructureReader->getStructure();
?>
<#5525>
<?php
require_once "./Services/AssessmentQuestion/src/Infrastructure/Persistance/EventStore/QuestionEventStoreAr.php";
ILIAS\AssessmentQuestion\Infrastructure\Persistence\EventStore\QuestionEventStoreAr::updateDB();
?>
<#5526>
<?php
require_once "./Services/AssessmentQuestion/src/Infrastructure/Persistance/Projection/QuestionListItemAr.php";
ILIAS\AssessmentQuestion\Infrastructure\Persistence\Projection\QuestionListItemAr::updateDB();
?>
<#5527>
<?php
require_once "./Services/AssessmentQuestion/src/Infrastructure/Persistance/Projection/QuestionListItemAr.php";
ILIAS\AssessmentQuestion\Infrastructure\Persistence\Projection\QuestionAr::updateDB();
?>
<#5528>
<?php
//Migrate Contentpage Definition - Question Page - qpl -> asq
$ilDB->query("UPDATE copg_pobj_def SET parent_type = 'asqq' where component = 'Modules/TestQuestionPool' AND class_name = 'ilAssQuestionPage'");
$ilDB->query("UPDATE copg_pobj_def SET component = 'Services/AssessmentQuestion',  class_name = '".addslashes('\ILIAS\AssessmentQuestion\UserInterface\Web\Page\Page')."', directory = 'src/UserInterface/Web/Page' where parent_type = 'asqq'");
$ilDB->query("UPDATE page_object SET parent_type = 'asqq' where parent_type = 'qpl' and page_id >= 0");
?>
<#5529>
<?php
//Migrate Feedback Page afbg -> asqg
$ilDB->query("UPDATE copg_pobj_def SET parent_type ='asqg' where component = 'Modules/TestQuestionPool' AND class_name = 'ilAssGenFeedbackPage'");
$ilDB->query("UPDATE copg_pobj_def SET component = 'Services/AssessmentQuestion',  class_name = '".addslashes('\ILIAS\AssessmentQuestion\UserInterface\Web\Page\Page')."', directory = 'src/UserInterface/Web/Page' where parent_type = 'asqg'");
$ilDB->query("UPDATE page_object SET parent_type = 'asqg' where parent_type = 'afbg' and page_id >= 0");
?>
<#5530>
<?php
//Migrate ilAssSpecFeedbackPage qfbs -> asqa
$ilDB->query("UPDATE copg_pobj_def SET parent_type ='asqa' where component = 'Modules/TestQuestionPool' AND class_name = 'ilAssSpecFeedbackPage'");
$ilDB->query("UPDATE copg_pobj_def SET component = 'Services/AssessmentQuestion',  class_name = '".addslashes('\ILIAS\AssessmentQuestion\UserInterface\Web\Page\Page')."', directory = 'src/UserInterface/Web/Page' where parent_type = 'asqa'");
$ilDB->query("UPDATE page_object SET parent_type = 'asqa' where parent_type = 'qfbs' and page_id >= 0");
?>
<#5531>
<?php
if( !$ilDB->tableColumnExists('qpl_questions', 'migrated_to_asq') )
{
    $ilDB->addTableColumn('qpl_questions', 'migrated_to_asq', array(
        'type' => 'integer',
        'notnull' => false,
        'length' => 1,
        'default' => 0
    ));
}
?>
<#5532>
<?php
$query = "Select * from qpl_questions as question
inner join qpl_qst_sc as sc_data on sc_data.question_fi = question.question_id
/*
left join qpl_fb_generic as fb_generic on fb_generic.question_fi = question.question_id
left join qpl_fb_specific as fb_specific on fb_specific.question_fi = question.question_id*/";
$res = $ilDB->query($query);
$questions = [];
$uuids = [];
while($rec = $ilDB->fetchAssoc($res)) {
    //uuid anlegen
    $id = new ILIAS\AssessmentQuestion\CQRS\Aggregate\DomainObjectId();
    $questions[] = $rec;
    $uuids[$rec['question_id']] = $id;
}


$query = "Select * from qpl_questions as question
inner join qpl_qst_sc as sc_data on sc_data.question_fi = question.question_id
inner join qpl_a_sc as sc_answers on sc_answers.question_fi = question.question_id";
$res = $ilDB->query($query);
while($rec = $ilDB->fetchAssoc($res)) {
    $questions_answer_options[$rec['question_id']][] = $rec;
}


require_once "./Services/AssessmentQuestion/src/Infrastructure/Persistance/EventStore/QuestionEventStoreAr.php";
require_once "./Services/AssessmentQuestion/src/Application/AuthoringApplicationService.php";
require_once "./Services/AssessmentQuestion/PublicApi/Common/AssessmentEntityId.php";
require_once "./Services/AssessmentQuestion/src/Application/AuthoringApplicationService.php";
require_once "./Services/AssessmentQuestion/src/UserInterface/Web/AsqGUIElementFactory.php";
require_once "./Services/AssessmentQuestion/PublicApi/Common/entityIdBuilder.php";
foreach($questions as $question) {

    $asq_appliction_service = new ILIAS\AssessmentQuestion\Application\AuthoringApplicationService($question['obj_fi'], $question['owner'], 'de');

    //create question
    $id = new ILIAS\AssessmentQuestion\CQRS\Aggregate\DomainObjectId();
    $asq_appliction_service->CreateQuestion($uuids[$question['question_id']],
        $question['obj_fi'],
        $question['type'],
        $question['question_id'],
        ILIAS\AssessmentQuestion\UserInterface\Web\AsqGUIElementFactory::TYPE_SINGLE_CHOICE,
        ILIAS\AssessmentQuestion\DomainModel\ContentEditingMode::RTE_TEXTAREA
    );

    //set question data
    $question_dto = $asq_appliction_service->getQuestion($uuids[$question['question_id']]->getId());
    $question_data = ILIAS\AssessmentQuestion\DomainModel\QuestionData::create(
        $question['title'],
        $question['question_text'],
        $question['author'],
        $question['description'],
        $question['working_time']
    );
    $question_dto->setData($question_data);

    //set answer options
    $anser_options_dto = new ILIAS\AssessmentQuestion\DomainModel\Answer\Option\AnswerOptions();
    foreach ($questions_answer_options[$question['question_id']] as $question_answer) {
        $display_definition = new ILIAS\AssessmentQuestion\UserInterface\Web\Component\Editor\ImageAndTextDisplayDefinition(strval($question_answer['answertext']), '');

        $scoring_definition = new ILIAS\AssessmentQuestion\DomainModel\Scoring\MultipleChoiceScoringDefinition();

        $question_answer_option_dto = new \ILIAS\AssessmentQuestion\DomainModel\Answer\Option\AnswerOption($question_answer['answer_id'], $display_definition, $scoring_definition);
        $anser_options_dto->addOption($question_answer_option_dto);
    }
    $question_dto->setAnswerOptions($anser_options_dto);
    /* ILIAS\AssessmentQuestion\UserInterface\Web\Component\Editor\MultipleChoiceEditorConfiguration::create(
         $rec['shuffle'],
         $rec['nr_of_tries'],
         $rec['thumb_size']
     );*/

    $asq_appliction_service->SaveQuestion($question_dto);
}