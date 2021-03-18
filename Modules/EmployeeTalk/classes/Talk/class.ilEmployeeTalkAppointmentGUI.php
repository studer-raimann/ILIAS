<?php
declare(strict_types=1);

use ILIAS\EmployeeTalk\UI\ControlFlowCommandHandler;
use ILIAS\EmployeeTalk\UI\ControlFlowCommand;

/**
 * Class ilEmployeeTalkAppointmentGUI
 *
 * @ilCtrl_IsCalledBy ilEmployeeTalkAppointmentGUI: ilObjEmployeeTalkGUI
 */
final class ilEmployeeTalkAppointmentGUI implements ControlFlowCommandHandler
{
    const EDIT_MODE_APPOINTMENT = 'appointment';
    const EDIT_MODE_SERIES = 'series';
    const EDIT_MODE = 'edit-mode';

    /**
     * @var ilGlobalTemplateInterface $template
     */
    private $template;
    /**
     * @var ilLanguage $language
     */
    private $language;
    /**
     * @var ilCtrl $controlFlow
     */
    private $controlFlow;
    /**
     * @var ilTabsGUI
     */
    private $tabs;

    /**
     * ilEmployeeTalkAppointmentGUI constructor.
     * @param ilGlobalTemplateInterface $template
     * @param ilLanguage                $language
     * @param ilCtrl                    $controlFlow
     * @param ilTabsGUI                 $tabs
     */
    public function __construct(
        ilGlobalTemplateInterface $template,
        ilLanguage $language,
        ilCtrl $controlFlow,
        ilTabsGUI $tabs
    ) {
        $this->template = $template;
        $this->language = $language;
        $this->controlFlow = $controlFlow;
        $this->tabs = $tabs;

        $this->language->loadLanguageModule('cal');
    }

    function executeCommand(): bool {
        $cmd = $this->controlFlow->getCmd(ControlFlowCommand::DEFAULT);
        $params = $this->controlFlow->getParameterArrayByClass(strtolower(self::class));

        $backClass = strtolower(ilObjEmployeeTalkGUI::class);
        $this->controlFlow->setParameterByClass($backClass, 'ref_id', $params['ref_id']);
        $this->tabs->setBackTarget($this->language->txt('back'), $this->controlFlow->getLinkTargetByClass(strtolower(ilObjEmployeeTalkGUI::class), ControlFlowCommand::DEFAULT));

        switch ($this->editMode()) {
            case self::EDIT_MODE_SERIES:
                return $this->executeSeriesCommand($cmd);
            case self::EDIT_MODE_APPOINTMENT:
                return $this->executeAppointmentCommand($cmd);
            default:
                $this->controlFlow->redirectByClass(strtolower(ilObjEmployeeTalkGUI::class), ControlFlowCommand::DEFAULT);
                return true;
        }
    }

    private function executeSeriesCommand(string $cmd): bool {
        $this->template->setTitle($this->language->txt('etal_date_series_edit'));

        switch ($cmd) {
            case ControlFlowCommand::UPDATE_INDEX:
                $this->editSeries();
                return true;
            case ControlFlowCommand::UPDATE:
                $this->updateSeries();
                return true;
        }

        return false;
    }

    private function executeAppointmentCommand(string $cmd): bool {
        $this->template->setTitle($this->language->txt('etal_date_appointment_edit'));

        switch ($cmd) {
            case ControlFlowCommand::UPDATE_INDEX:
                $this->editAppointment();
                return true;
            case ControlFlowCommand::UPDATE:
                $this->updateAppointment();
                return true;
        }

        return false;
    }

    private function editSeries(): void {

    }

    private function updateSeries(): void {

    }

    private function editAppointment(): void {
        $form = new ilPropertyFormGUI();
        $editMode = $this->getEditModeParameter(ilEmployeeTalkAppointmentGUI::EDIT_MODE_APPOINTMENT);
        $form->setFormAction($this->controlFlow->getFormActionByClass(
            strtolower(self::class)
        ) . $editMode);

        $header = new ilFormSectionHeaderGUI();
        $header->setTitle($this->language->txt('appointment'));
        $form->addItem($header);
        //$cal = new ilRecurrenceInputGUI("Calender", "etal_cal");
        //$a_form->addItem($cal);

        $this->template->addJavaScript('./Services/Form/js/date_duration.js');
        $dur = new ilDateDurationInputGUI($this->language->txt('cal_fullday'), 'event');
        $dur->setRequired(true);
        $dur->enableToggleFullTime(
            $this->language->txt('cal_fullday_title'), true
        );
        $dur->setShowTime(true);
        // $dur->setStart($this->app->getStart());
        // $dur->setEnd($this->app->getEnd());
        $form->addItem($dur);
        $form->addCommandButton(ControlFlowCommand::UPDATE, $this->language->txt('save'), 'etal_date_save');
        $this->template->setContent($form->getHTML());
    }

    private function updateAppointment(): void {
        $this->controlFlow->redirectToURL(
            $this->controlFlow->getLinkTargetByClass(strtolower(self::class),
                ControlFlowCommand::UPDATE_INDEX) . $this->getEditModeParameter(ilEmployeeTalkAppointmentGUI::EDIT_MODE_APPOINTMENT)
        );
    }

    private function editMode(): string {
        return filter_input(INPUT_GET, self::EDIT_MODE, FILTER_CALLBACK, ['options' => function(string $value) {
                if ($value === self::EDIT_MODE_SERIES || $value === self::EDIT_MODE_APPOINTMENT) {
                    return $value;
                }

                return 'invalid';
            }]) ?? 'invalid';
    }

    private function getEditModeParameter(string $mode): string {
        return '&' . ilEmployeeTalkAppointmentGUI::EDIT_MODE . '=' . $mode;
    }
}