<?php

/**
 * Class ilDclCreateViewTableGUI
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ilDclCreateViewTableGUI extends ilTable2GUI
{
    public function __construct(ilDclCreateViewDefinitionGUI $a_parent_obj)
    {
        global $DIC;
        $lng = $DIC['lng'];
        $ilCtrl = $DIC['ilCtrl'];
        parent::__construct($a_parent_obj);

        $this->setId('dcl_tableviews');
        $this->setTitle($lng->txt('dcl_tableview_fieldsettings'));
        $this->addColumn($lng->txt('dcl_tableview_fieldtitle'), null, 'auto');
        $this->addColumn($lng->txt('dcl_tableview_locked'), null, 'auto');
        $this->addColumn($lng->txt('dcl_tableview_required'), null, 'auto');
        $this->addColumn($lng->txt('dcl_tableview_default_value'), null, 'auto');
        $this->addColumn($lng->txt('dcl_tableview_visible'), null, 'auto');

        $ilCtrl->saveParameter($this, 'tableview_id');
        $this->setFormAction($ilCtrl->getFormActionByClass('ildclcreateviewdefinitiongui'));
        $this->addCommandButton('saveTable', $lng->txt('dcl_save'));

        $this->setExternalSegmentation(true);
        $this->setExternalSorting(true);

        $this->setRowTemplate('tpl.tableview_create_view.html', 'Modules/DataCollection');
        $this->setTopCommands(true);
        $this->setEnableHeader(true);
        $this->setShowRowsSelector(false);
        $this->setShowTemplates(false);
        $this->setEnableHeader(true);
        $this->setEnableTitle(true);
        $this->setDefaultOrderDirection('asc');

        $this->parseData($a_parent_obj->tableview->getFieldSettings());
    }


    public function parseData($data)
    {
        $this->setData($data);
    }


    /**
     * @param ilDclTableViewFieldSetting $a_set
     */
    public function fillRow($a_set)
    {
        $field = $a_set->getFieldObject();

        if (!$field->isStandardField()) {
            $this->tpl->setVariable('IS_LOCKED', $a_set->isLocked() ? 'checked' : '');
            $this->tpl->setVariable('IS_REQUIRED', $a_set->isRequired() ? 'checked' : '');
            $this->tpl->setVariable('DEFAULT_VALUE', $a_set->getDefaultValue());
            $this->tpl->setVariable('IS_VISIBLE', $a_set->isVisibleCreate() ? 'checked' : '');
        } else {
            $this->tpl->setVariable('HIDDEN', 'hidden');
        }

        $this->tpl->setVariable('FIELD_ID', $a_set->getField());
        $this->tpl->setVariable('TITLE', $field->getTitle());
        $this->tpl->parseCurrentBlock();
    }
}