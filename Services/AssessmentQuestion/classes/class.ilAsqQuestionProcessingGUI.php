<?php

/* Copyright (c) 1998-2019 ILIAS open source, Extended GPL, see docs/LICENSE */

use ILIAS\AssessmentQuestion\Application\ProcessingApplicationService;
use ILIAS\AssessmentQuestion\UserInterface\Web\Toolbar\QuestionProcessingToolbarGUI;
use ILIAS\Services\AssessmentQuestion\PublicApi\Common\AuthoringContextContainer;
use ILIAS\Services\AssessmentQuestion\PublicApi\Common\AssessmentEntityId;
use ILIAS\Services\AssessmentQuestion\PublicApi\Authoring\AuthoringService;
use ILIAS\AssessmentQuestion\UserInterface\Web\Component\QuestionComponent;
use ILIAS\AssessmentQuestion\Application\AuthoringApplicationService;
use ILIAS\AssessmentQuestion\DomainModel\QuestionDto;
use ILIAS\AssessmentQuestion\DomainModel\QuestionPlayConfiguration;
use ILIAS\AssessmentQuestion\DomainModel\Answer\Answer;
use ILIAS\Services\AssessmentQuestion\PublicApi\Common\ProcessingContextContainer;
use ILIAS\Services\AssessmentQuestion\PublicApi\Common\QuestionCommands;
use ILIAS\Services\AssessmentQuestion\PublicApi\Common\QuestionConfig;
use ILIAS\UI\NotImplementedException;

/**
 * Class ilAsqQuestionProcessingGUI
 *
 * @author       studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author       Adrian Lüthi <al@studer-raimann.ch>
 * @author       Björn Heyser <bh@bjoernheyser.de>
 * @author       Martin Studer <ms@studer-raimann.ch>
 * @author       Theodor Truffer <tt@studer-raimann.ch>
 *
 * @ilCtrl_Calls ilAsqQuestionProcessingGUI: ilAsqQuestionPageGUI
 */
class ilAsqQuestionProcessingGUI
{

    const QUESTION_FORM_ID = 'asqQuestionForm';

    const CMD_SHOW_QUESTION = 'showQuestion';
    const CMD_SAVE_ANSWER = 'saveAnswer';
    const CMD_SHWOW_FEEDBACK = 'showFeedback';
    const CMD_DETECT_CHANGES = 'detectChanges';
    const CMD_PREVIOUS_QUESTION = 'previousQuestion';
    const CMD_NEXT_QUESTION = 'nextQuestion';
    const CMD_FINISH_TEST_PASS = 'finishTestPass';
    //Actions
    const CMD_REVERT_CHANGES = 'revertChanges';
    //const CMD_SCORE_PREVIEW = 'scorePreview';
    /**
     * @var ProcessingApplicationService
     */
    protected $processing_application_service;
    /**
     * @var string
     */
    protected $revision_key;
    /**
     * @var int
     */
    protected $attempt_number;
    /**
     * @var QuestionConfig
     */
    protected $question_config;
    /**
     * @var QuestionCommands
     */
    protected $question_comands;


    /**
     * ilAsqQuestionCreationGUI constructor.
     *
     * @param AuthoringContextContainer $contextContainer
     */
    public function __construct(
        string $question_revision_key,
        int $attempt_number,
        ProcessingContextContainer $processing_context_container,
        QuestionConfig $question_config
    ) {
        global $DIC;

        //TODO - weshalb ist language asq nicht immer geladen?
        $DIC->language()->loadLanguageModule('asq');

        //we could use this in future in constructer
        $lng_key = $DIC->language()->getDefaultLanguage();

        $this->processing_application_service = new ProcessingApplicationService($processing_context_container->getObjId(), $processing_context_container->getActorId(), $attempt_number, $lng_key);
        $this->revision_key = $question_revision_key;
        $this->question_config = $question_config;

        $this->question_comands = new QuestionCommands();
    }


    /**
     * @throws ilAsqException
     */
    public function executeCommand()
    {
        global $DIC;
        /* @var ILIAS\DI\Container $DIC */

        switch ($DIC->ctrl()->getCmd()) {
            case self::CMD_SAVE_ANSWER:
                $this->saveAnswer();
                break;
            case self::CMD_SHWOW_FEEDBACK:
                $this->showFeedback();
                break;
            case self::CMD_DETECT_CHANGES:
                $this->detectChanges();
                break;
            case self::CMD_REVERT_CHANGES:
                $this->revertChanges();
                break;
            case self::CMD_NEXT_QUESTION:
                $this->nextQuestion();
                break;
            case self::CMD_PREVIOUS_QUESTION:
                $this->previousQuestion();
                break;
            case self::CMD_FINISH_TEST_PASS:
                $this->finishTestPass();
                break;
            case self::CMD_SHOW_QUESTION:
            default:
                $this->showQuestion();
                break;
        }
    }


    public function showQuestion()
    {
        global $DIC;
        $DIC->ui()->mainTemplate()->setContent($this->getQuestionTpl()->get());
    }


    protected function saveAnswer()
    {
        throw new NotImplementedException();
    }


    public function revertChanges()
    {
        $this->showQuestion();
    }


    public function detectChanges()
    {
       exit;
    }


    public function showFeedback()
    {
        global $DIC;
        $question_tpl = $this->getQuestionTpl();
        $question_dto = $this->processing_application_service->getQuestion($this->revision_key);

        $feedback_component = $this->processing_application_service->getFeedbackComponent($question_dto);
        $question_tpl->setCurrentBlock('instant_feedback');
        $question_tpl->setVariable('INSTANT_FEEDBACK', $feedback_component->getHtml());
        $question_tpl->parseCurrentBlock();

        $DIC->ui()->mainTemplate()->setContent($question_tpl->get());
    }

    public function nextQuestion() {
        $this->saveAnswer();

        global $DIC;
        $DIC->ctrl()->redirectByClass(
            $this->question_config->getShowNextQuestionAction()->getCtrlStack(),
            $this->question_config->getShowNextQuestionAction()->getCommand());
    }

    public function previousQuestion() {

        $this->saveAnswer();

        global $DIC;
        $DIC->ctrl()->redirectByClass(
            $this->question_config->getShowPreviousQuestionAction()->getCtrlStack(),
            $this->question_config->getShowPreviousQuestionAction()->getCommand());
    }

    public function finishTestPass() {
        global $DIC;

        $this->saveAnswer();

        $DIC->ctrl()->redirectByClass(
            $this->question_config->getShowFinishTestSessionAction()->getCtrlStack(),
            $this->question_config->getShowFinishTestSessionAction()->getCommand()
        );
    }


    /**
     * @return ilTemplate
     * @throws ilTemplateException
     */
    private function getQuestionTpl() : ilTemplate
    {
        global $DIC;
        /* @var \ILIAS\DI\Container $DIC */
        $question_dto = $this->processing_application_service->getQuestion($this->revision_key);

        //TODO
        $question_commands = new QuestionCommands();

        // Normal questions: changes are done in form fields an can be detected there
        $config['withFormChangeDetection'] = true; //$questionConfig->isFormChangeDetectionEnabled();

        // Flash and Java questions: changes are directly sent to ilias and have to be polled from there
        $config['withBackgroundChangeDetection'] = true; //$questionConfig->isBackgroundChangeDetectionEnabled();
        $config['backgroundDetectorUrl'] = $DIC->ctrl()->getLinkTarget($this, ilTestPlayerCommands::DETECT_CHANGES, '', true);

        // Forced feedback will change the navigation saving command
        $config['forcedInstantFeedback'] = true; //$this->object->isForceInstantFeedbackEnabled();
        $config['nextQuestionLocks'] = true; //$this->object->isFollowupQuestionAnswerFixationEnabled();

        if(count($this->question_config->getJavaScriptOnLoadPaths()) > 0) {
            foreach($this->question_config->getJavaScriptOnLoadPaths() as $js_path) {
                $DIC->ui()->mainTemplate()->addJavascript($js_path);
            }
        }
        //TODO JS on load code
        /*
        $DIC->ui()->mainTemplate()->addOnLoadCode('il.QuestionEditControl.init(' . json_encode($config) . ')');
        */

        $question_page = $this->processing_application_service->getQuestionPresentation($question_dto, $this->question_config, $question_commands);

        if(is_object($this->question_config->getQuestionPageActionMenu())) {
            //TODO Move template
            $tpl = new ilTemplate('tpl.tst_question_actions.html', true, true, 'Modules/Test');
            $tpl->setVariable('ACTION_MENU',$this->question_config->getQuestionPageActionMenu()->getHTML());
            $question_page->setQuestionActionsHTML($tpl->get());
        }


        $tpl_question_navigation_html = "";
        if (!is_null($this->question_config->getShowNextQuestionAction()) || !is_null($this->question_config->getShowPreviousQuestionAction())) {
            $tpl_question_navigation = new ilTemplate('tpl.question_navigation.html', true, true, 'Services/AssessmentQuestion');

            if (!is_null($this->question_config->getShowNextQuestionAction())) {
                //The Redirect to the parent GUI is after saveing. see saveAnswer
                $button_next = $DIC->ui()->factory()->button()->primary($DIC->language()->txt('next_question'), '');
                $tpl_question_navigation->setVariable('BTN_NEXT', $DIC->ui()->renderer()->render($button_next));
            }

            if (!is_null($this->question_config->getShowPreviousQuestionAction())) {
                $btn_prev = $DIC->ui()->factory()->button()->standard($DIC->language()->txt('previous_question'),$DIC->ctrl()->getLinkTarget($this, self::CMD_PREVIOUS_QUESTION));
                $tpl_question_navigation->setVariable('BTN_PREV', $DIC->ui()->renderer()->render($btn_prev));
            }

            $tpl_question_navigation_html = $tpl_question_navigation->get();
        }

        $question_processing_toolbar = new QuestionProcessingToolbarGUI($this->question_config, $this);

        $tpl = new ilTemplate('tpl.question_container.html', true, true, 'Services/AssessmentQuestion');
        $tpl->setVariable('FORMACTION', $DIC->ctrl()->getFormAction($this, self::CMD_NEXT_QUESTION));
        $tpl->setVariable('FORMID', self::QUESTION_FORM_ID);
        $tpl->setVariable('QUESTION_PROCESSING_TOOLBAR', $question_processing_toolbar->getHTML());
        $tpl->setVariable('QUESTION_NAVIGATION', $tpl_question_navigation_html);
        $tpl->setVariable('QUESTION_OUTPUT', $question_page->showPage());

        return $tpl;
    }


    public function scorePreview()
    {
        global $DIC;
        $question_dto = $this->processing_application_service->getQuestion($this->question_id->getId());
        $scoring_component = $this->processing_application_service->getScoringComponent($question_dto);

        /**
         * TODO: we should think about the QuestionComponent again (later).
         * Currently it handles rendering of the question inputs
         * as well as reading from request,
         * altough the answer behavior is settable from outside.
         */

        $answer = new Answer(
            $this->context_container->getActorId(),
            $this->question_id->getId(),
            $question_dto->getRevisionId(),
            $this->context_container->getObjId(),
           0,
            $this->questionComponent->readAnswer()
        );

        $this->questionComponent->setAnswer($answer);

        $scoring_class = QuestionPlayConfiguration::getScoringClass($this->questionComponent->getQuestionDto()->getPlayConfiguration());
        $scoring = new $scoring_class($this->questionComponent->getQuestionDto());

        ilUtil::sendInfo("Score: " . $scoring->score($answer));

        $this->showQuestion();
    }
}