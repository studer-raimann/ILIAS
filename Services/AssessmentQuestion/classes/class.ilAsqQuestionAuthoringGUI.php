<?php
declare(strict_types=1);

/* Copyright (c) 1998-2013 ILIAS open source, Extended GPL, see docs/LICENSE */


use ILIAS\Services\AssessmentQuestion\PublicApi\Authoring\AuthoringQuestion;
use ILIAS\Services\AssessmentQuestion\PublicApi\Common\AuthoringContextContainer;
use srag\CQRS\Aggregate\DomainObjectId;

/**
 * Class ilAsqQuestionAuthoringGUI
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 *
 * @ilCtrl_Calls ilAsqQuestionAuthoringGUI: ilAsqQuestionCreationGUI
 * @ilCtrl_Calls ilAsqQuestionAuthoringGUI: ilAsqQuestionPreviewGUI
 * @ilCtrl_Calls ilAsqQuestionAuthoringGUI: ilAsqQuestionPageGUI
 * @ilCtrl_Calls ilAsqQuestionAuthoringGUI: ilAsqQuestionConfigEditorGUI
 * @ilCtrl_Calls ilAsqQuestionAuthoringGUI: ilAsqQuestionFeedbackEditorGUI
 * @ilCtrl_Calls ilAsqQuestionAuthoringGUI: AsqQuestionHintEditorGUI
 * @ilCtrl_Calls ilAsqQuestionAuthoringGUI: ilAsqQuestionRecapitulationEditorGUI
 * @ilCtrl_Calls ilAsqQuestionAuthoringGUI: ilAsqQuestionStatisticsGUI
 * @ilCtrl_Calls ilAsqQuestionAuthoringGUI: ilCommonActionDispatcherGUI
 */
class ilAsqQuestionAuthoringGUI
{
    const TAB_ID_PREVIEW = 'qst_preview_tab';
    const TAB_ID_PAGEVIEW = 'qst_pageview_tab';
    const TAB_ID_CONFIG = 'qst_config_tab';
    const TAB_ID_FEEDBACK = 'qst_feedback_tab';
    const TAB_ID_HINTS = 'qst_hints_tab';
    const TAB_ID_RECAPITULATION = 'qst_recapitulation_tab';
    const TAB_ID_STATISTIC = 'qst_statistic_tab';

    const VAR_QUESTION_ID = "question_id";

    const CMD_REDRAW_HEADER_ACTION_ASYNC = '';

    /**
     * @var AuthoringContextContainer
     */
	protected $authoring_context_container;
    /**
     * @var DomainObjectId
     */
    protected $question_id;
    /**
     * @var string
     */
    protected $lng_key;

    /**
     * ilAsqQuestionAuthoringGUI constructor.
     *
     * @param AuthoringContextContainer $authoring_context_container
     */
	function __construct(AuthoringContextContainer $authoring_context_container)
	{
	    global $DIC; /* @var ILIAS\DI\Container $DIC */

	    $this->authoring_context_container = $authoring_context_container;

	    //we could use this in future in constructer
	    $this->lng_key = $DIC->language()->getDefaultLanguage();

        $this->question_id = $this->currentOrNewQuestionId();
        
        $DIC->language()->loadLanguageModule('asq');
    }

    /**
     * Returns the current question_uuid or a new one if no current exists
     *
     * @return DomainObjectId
     */
    private function currentOrNewQuestionId() : DomainObjectId
    {
        if (isset($_GET[\ilAsqQuestionAuthoringGUI::VAR_QUESTION_ID])) {
            return new DomainObjectId($_GET[\ilAsqQuestionAuthoringGUI::VAR_QUESTION_ID]);
        }
        
        return new DomainObjectId();
    }

    /**
     * @throws ilCtrlException
     */
	public function executeCommand()
	{
		global $DIC; /* @var ILIAS\DI\Container $DIC */

        $DIC->ctrl()->setParameter(
            $this, self::VAR_QUESTION_ID, $this->question_id->getId()
        );

		switch( $DIC->ctrl()->getNextClass() )
        {
            case strtolower(ilAsqQuestionCreationGUI::class):

                $gui = new ilAsqQuestionCreationGUI($this->authoring_context_container);

                $DIC->ctrl()->forwardCommand($gui);

                break;

            case strtolower(ilAsqQuestionPreviewGUI::class):

                $this->initHeaderAction();
                $this->initAuthoringTabs();
                $DIC->tabs()->activateTab(self::TAB_ID_PREVIEW);

                $gui = new ilAsqQuestionPreviewGUI($this->question_id);

                $DIC->ctrl()->forwardCommand($gui);

                break;

            case strtolower(ilAsqQuestionPageGUI::class):

                $this->initHeaderAction();
                $this->initAuthoringTabs();
                $DIC->tabs()->activateTab(self::TAB_ID_PAGEVIEW);

                $gui = $DIC->assessment()->question()->getQuestionPage(
                    $DIC->assessment()->question()->getQuestionByQuestionId($this->question_id->getId()));

                if (strlen($DIC->ctrl()->getCmd()) == 0 && !isset($_POST["editImagemapForward_x"]))
                {
                    // workaround for page edit imagemaps, keep in mind

                    $DIC->ctrl()->setCmdClass(strtolower(get_class($gui)));
                    $DIC->ctrl()->setCmd('preview');
                }

                $html = $DIC->ctrl()->forwardCommand($gui);
                $DIC->ui()->mainTemplate()->setContent($html);

                break;

            case strtolower(ilAsqQuestionConfigEditorGUI::class):

                $this->initHeaderAction();
                $this->initAuthoringTabs();
                $DIC->tabs()->activateTab(self::TAB_ID_CONFIG);

                $gui = new ilAsqQuestionConfigEditorGUI(
                    $this->authoring_context_container,
                    $this->question_id);
                $DIC->ctrl()->forwardCommand($gui);

                break;

            case strtolower(ilAsqQuestionFeedbackEditorGUI::class):

                $this->initHeaderAction();
                $this->initAuthoringTabs();
                $DIC->tabs()->activateTab(self::TAB_ID_FEEDBACK);

                $gui = new ilAsqQuestionFeedbackEditorGUI(
                    $DIC->assessment()->question()->getQuestionByQuestionId($this->question_id->getId())
                );
                $DIC->ctrl()->forwardCommand($gui);

                break;

            case strtolower(AsqQuestionHintEditorGUI::class):

                $this->initHeaderAction();
                $this->initAuthoringTabs();
                $DIC->tabs()->activateTab(self::TAB_ID_HINTS);

                $gui = new AsqQuestionHintEditorGUI($$DIC->assessment()->question()->getQuestionByQuestionId($this->question_id->getId()));
                $DIC->ctrl()->forwardCommand($gui);

                break;

            case strtolower(ilCommonActionDispatcherGUI::class):

                $gui = ilCommonActionDispatcherGUI::getInstanceFromAjaxCall();
                $DIC->ctrl()->forwardCommand($gui);

                break;

            case strtolower(self::class):
            default:

                $cmd = $DIC->ctrl()->getCmd();
                $this->{$cmd}();
        }
	}


    protected function redrawHeaderAction()
    {
        global $DIC; /* @var ILIAS\DI\Container $DIC */
        echo $this->getHeaderAction() . $DIC->ui()->mainTemplate()->getOnLoadCodeForAsynch();
        exit;
    }


    protected function initHeaderAction()
    {
        global $DIC; /* @var ILIAS\DI\Container $DIC */

        $DIC->ui()->mainTemplate()->setVariable(
            'HEAD_ACTION', $this->getHeaderAction()
        );

        $notesUrl = $DIC->ctrl()->getLinkTargetByClass(
            array('ilCommonActionDispatcherGUI', 'ilNoteGUI'), '', '', true, false
        );

        ilNoteGUI::initJavascript($notesUrl,IL_NOTE_PUBLIC, $DIC->ui()->mainTemplate());

        $redrawActionsUrl = $DIC->ctrl()->getLinkTarget(
            $this, self::CMD_REDRAW_HEADER_ACTION_ASYNC, '', true
        );

        $DIC->ui()->mainTemplate()->addOnLoadCode("il.Object.setRedrawAHUrl('$redrawActionsUrl');");
    }


    protected function getHeaderAction() : string
    {
        global $DIC; /* @var ILIAS\DI\Container $DIC */

        //$question = $this->authoring_application_service->GetQuestion($this->question_id->getId());

        /**
         * TODO: Get the old integer id of the question.
         * We still need the former integer sequence id of the question,
         * since several other services in ilias does only work with an int id.
         */

        //$integerQuestionId = $question->getLegacyIntegerId(); // or similar
        $integerQuestionId = 0;

        $dispatcher = new ilCommonActionDispatcherGUI(
            ilCommonActionDispatcherGUI::TYPE_REPOSITORY, $DIC->access(),
            $this->authoring_context_container->getObjType(),
            $this->authoring_context_container->getRefId(),
            $this->authoring_context_container->getObjId()
        );

        $dispatcher->setSubObject('quest', $integerQuestionId);

        $ha = $dispatcher->initHeaderAction();
        $ha->enableComments(true, false);

        return $ha->getHeaderAction($DIC->ui()->mainTemplate());
    }


    protected function initAuthoringTabs()
    {
        global $DIC; /* @var ILIAS\DI\Container $DIC */

        $question = new AuthoringQuestion();
        $question_dto = $DIC->assessment()->question()->getQuestionByQuestionId($this->question_id->getId());
        
        $DIC->tabs()->clearTargets();

        $DIC->tabs()->setBackTarget(
            $this->authoring_context_container->getBackLink()->getLabel(),
            $this->authoring_context_container->getBackLink()->getAction()
        );

        if(is_object($question_dto->getData()) > 0 && $this->authoring_context_container->hasWriteAccess() )
        {
            $link = $question->getEditPageLink($this->question_id->getId());
            $DIC->tabs()->addTab(self::TAB_ID_PAGEVIEW, $link->getLabel(), $link->getAction());
        }
        if(is_object($question_dto->getData()) > 0) {
            $link = $question->getPreviewLink($this->question_id->getId());
            $DIC->tabs()->addTab(self::TAB_ID_PREVIEW, $link->getLabel(), $link->getAction());
        }
        if( $this->authoring_context_container->hasWriteAccess() )
        {
            $link = $question->getEditLink($this->question_id->getId());
            $DIC->tabs()->addTab(self::TAB_ID_CONFIG, $link->getLabel(), $link->getAction());
        }
        if(is_object($question_dto->getData()) > 0) {
            $link = $question->getEditFeedbacksLink($this->question_id->getId());
            $DIC->tabs()->addTab(self::TAB_ID_FEEDBACK, $link->getLabel(), $link->getAction());
        }
        if(is_object($question_dto->getData()) > 0) {
            $link = $question->getEditHintsLink($this->question_id->getId());
            $DIC->tabs()->addTab(self::TAB_ID_HINTS, $link->getLabel(), $link->getAction());
        }
    }
}