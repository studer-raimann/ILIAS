<?php
declare(strict_types=1);

namespace ILIAS\Services\AssessmentQuestion\PublicApi\Authoring;

use ILIAS\Services\AssessmentQuestion\PublicApi\Common\AuthoringContextContainer;
use ILIAS\Services\AssessmentQuestion\PublicApi\Common\QuestionConfig;
use ILIAS\UI\Component\Link\Standard as UiStandardLink;
use ilAsqQuestionAuthoringGUI;

/**
 * Class QuestionAuthoring
 *
 * @package ILIAS\Services\AssessmentQuestion\PublicApi
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class AuthoringQuestion
{
    public function getCreationLink(array $ctrl_stack) :UiStandardLink
    {
        global $DIC;

        array_push($ctrl_stack,ilAsqQuestionAuthoringGUI::class);
        array_push($ctrl_stack,\ilAsqQuestionCreationGUI::class);

        return $DIC->ui()->factory()->link()->standard(
            $DIC->language()->txt('asq_authoring_create_question_link'),
            $DIC->ctrl()->getLinkTargetByClass($ctrl_stack)
        );
    }

    public function getAuthoringGUI(
        UiStandardLink $container_back_link,
        int $container_ref_id,
        string $container_obj_type,
        QuestionConfig $question_config,
        bool $actor_has_write_access,
        array $afterQuestionCreationCtrlClassPath,
        string $afterQuestionCreationCtrlCommand
    ) : ilAsqQuestionAuthoringGUI
    {
        global $DIC;
        
        $authoringContextContainer = new AuthoringContextContainer(
            $container_back_link,
            $container_ref_id,
            $container_ref_id,
            $container_obj_type,
            $DIC->user()->getId(),
            $actor_has_write_access,
            $afterQuestionCreationCtrlClassPath,
            $afterQuestionCreationCtrlCommand
        );

        return new ilAsqQuestionAuthoringGUI($authoringContextContainer, $question_config, $this->authoring_question_after_save_command_handler);
    }

    /**
     * @return UiStandardLink
     */
    public function getEditLink(string $question_id, array $ctrl_stack = []) :UiStandardLink
    {
        global $DIC;
        array_push($ctrl_stack,ilAsqQuestionAuthoringGUI::class);
        array_push($ctrl_stack,\ilAsqQuestionConfigEditorGUI::class);

        $this->setQuestionUidParameter($question_id);

        return $DIC->ui()->factory()->link()->standard(
            $DIC->language()->txt('asq_authoring_tab_config'),
            $DIC->ctrl()->getLinkTargetByClass($ctrl_stack));
    }


    /**
     * @return UiStandardLink
     */
    //TODO this will not be the way! Do not save questions,
    // only simulate and show the points directly after submitting
    // Therefore, to Save Command has to
    public function getPreviewLink(string $question_id, array $ctrl_stack = []) : UiStandardLink
    {
        global $DIC;
        array_push($ctrl_stack,ilAsqQuestionAuthoringGUI::class);
        array_push($ctrl_stack,\ilAsqQuestionPreviewGUI::class);

        $this->setQuestionUidParameter($question_id);

        return $DIC->ui()->factory()->link()->standard(
            $DIC->language()->txt('asq_authoring_tab_preview'),
            $DIC->ctrl()->getLinkTargetByClass($ctrl_stack)
        );
    }

    /**
     * @return UiStandardLink
     */
    public function getEditPageLink(string $question_id) : UiStandardLink
    {
        global $DIC; /* @var \ILIAS\DI\Container $DIC */

        $this->setQuestionUidParameter($question_id);

        return $DIC->ui()->factory()->link()->standard(
            $DIC->language()->txt('asq_authoring_tab_pageview'),
            $DIC->ctrl()->getLinkTargetByClass(
                [ilAsqQuestionAuthoringGUI::class, \ilAsqQuestionPageGUI::class], 'edit'
            )
        );
    }


    /**
     * @return UiStandardLink
     */
    public function getEditFeedbacksLink(string $question_id) : UiStandardLink
    {
        global $DIC; /* @var \ILIAS\DI\Container $DIC */

        $this->setQuestionUidParameter($question_id);

        return $DIC->ui()->factory()->link()->standard(
            $DIC->language()->txt('asq_authoring_tab_feedback'),
            $DIC->ctrl()->getLinkTargetByClass([
                ilAsqQuestionAuthoringGUI::class, \ilAsqQuestionFeedbackEditorGUI::class
            ])
        );
    }


    /**
     * @return UiStandardLink
     */
    public function getEditHintsLink(string $question_id) : UiStandardLink
    {
        global $DIC; /* @var \ILIAS\DI\Container $DIC */

        $this->setQuestionUidParameter($question_id);

        return $DIC->ui()->factory()->link()->standard(
            $DIC->language()->txt('asq_authoring_tab_hints'),
            $DIC->ctrl()->getLinkTargetByClass([
                ilAsqQuestionAuthoringGUI::class, \AsqQuestionHintEditorGUI::class
            ])
        );
    }

    /**
     * sets the question uid parameter for the ctrl hub gui ilAsqQuestionAuthoringGUI
     */
    protected function setQuestionUidParameter(string $question_id)
    {
        global $DIC; /* @var \ILIAS\DI\Container $DIC */

        $DIC->ctrl()->setParameterByClass(
            ilAsqQuestionAuthoringGUI::class,
            ilAsqQuestionAuthoringGUI::VAR_QUESTION_ID,
            $question_id
        );
    }
}