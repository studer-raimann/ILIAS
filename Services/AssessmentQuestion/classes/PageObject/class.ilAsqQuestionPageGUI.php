<?php

/* Copyright (c) 1998-2019 ILIAS open source, Extended GPL, see docs/LICENSE */


use ILIAS\AssessmentQuestion\UserInterface\Web\Component\QuestionComponent;
use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class ilAsqQuestionPageGUI
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 *
 * @ilCtrl_Calls ilAsqQuestionPageGUI: ilPageEditorGUI
 * @ilCtrl_Calls ilAsqQuestionPageGUI: ilEditClipboardGUI
 * @ilCtrl_Calls ilAsqQuestionPageGUI: ilMDEditorGUI
 * @ilCtrl_Calls ilAsqQuestionPageGUI: ilPublicUserProfileGUI
 * @ilCtrl_Calls ilAsqQuestionPageGUI: ilNoteGUI
 * @ilCtrl_Calls ilAsqQuestionPageGUI: ilInternalLinkGUI
 * @ilCtrl_Calls ilAsqQuestionPageGUI: ilPropertyFormGUI
 */
class ilAsqQuestionPageGUI extends ilPageObjectGUI
{
    const PAGE_TYPE = 'asqq';

    const TEMP_PRESENTATION_TITLE_PLACEHOLDER = '___TEMP_PRESENTATION_TITLE_PLACEHOLDER___';

    /**
     * @var string
     */
    public $originalPresentationTitle = '';
    /**
     * @var bool
     */
    public $a_output = false;

    /**
     * @var QuestionComponent
     */
    private $component;

    /**
     * ilAsqQuestionPageGUI constructor.
     *
     * @param AsqPageObject $page
     */
    function __construct(int $parent_int_id, int $page_int_id)
    {
        /**
          * @var \ILIAS\DI\Container $DIC
        **/
        global $DIC;

        $this->createPageIfNotExists(self::PAGE_TYPE, $parent_int_id, $page_int_id);
        
        parent::__construct(self::PAGE_TYPE, $page_int_id, 0, false);

        $this->page_back_title = $this->lng->txt("page");

        // content and syntax styles
        $DIC->ui()->mainTemplate()->setCurrentBlock("ContentStyle");
        $DIC->ui()->mainTemplate()->setVariable("LOCATION_CONTENT_STYLESHEET", ilObjStyleSheet::getContentStylePath(0));
        $DIC->ui()->mainTemplate()->parseCurrentBlock();
        $DIC->ui()->mainTemplate()->setCurrentBlock("SyntaxStyle");
        $DIC->ui()->mainTemplate()->setVariable("LOCATION_SYNTAX_STYLESHEET", ilObjStyleSheet::getSyntaxStylePath());
        $DIC->ui()->mainTemplate()->parseCurrentBlock();
    }

    private function createPageIfNotExists(string $page_type, int $parent_int_id, int $page_int_id)
    {
        if (ilPageObject::_exists($page_type, $page_int_id) === false) {
            include_once("./Services/AssessmentQuestion/src/UserInterface/Web/Page/class.AsqPageObject.php");
            $page = new AsqPageObject();
            $page->setParentType($page_type);
            $page->setParentId($parent_int_id);
            $page->setId($page_int_id);
            
            $page->create();
        }
    }

    public function getOriginalPresentationTitle()
    {
        return $this->originalPresentationTitle;
    }

    public function setOriginalPresentationTitle($originalPresentationTitle)
    {
        $this->originalPresentationTitle = $originalPresentationTitle;
    }

    protected function isPageContainerToBeRendered()
    {
        return $this->getRenderPageContainer();
    }

    public function showPage()
    {
        /**
         * enable page toc as placeholder for info and actions block
         * @see self::insertPageToc
         */

        $config = $this->getPageConfig();
        $config->setEnablePageToc('y');
        $this->setPageConfig($config);

        return parent::showPage();
    }

    /**
     * support the addition of question info and actions below the title
     */

    /**
     * Set the HTML of a question info block below the title (number, status, ...)
     * @param string	$a_html
     */
    public function setQuestionInfoHTML($a_html)
    {
        $this->questionInfoHTML = $a_html;
    }

    /**
     * Set the HTML of a question actions block below the title
     * @param string 	$a_html
     */
    public function setQuestionActionsHTML($a_html)
    {
        $this->questionActionsHTML = $a_html;
    }

    function setQuestionComponent(QuestionComponent $component) {
        $this->component = $component;
        $this->setQuestionHTML([$this->getId() => $component->renderHtml()]);
    }
    
    function getQuestionComponent() : QuestionComponent {
        return $this->component;
    }
}
