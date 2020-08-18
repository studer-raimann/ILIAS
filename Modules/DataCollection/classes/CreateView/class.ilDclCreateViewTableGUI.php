<?php

/**
 * Class ilDclCreateViewTableGUI
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ilDclCreateViewTableGUI extends ilTable2GUI
{
    const VALID_DEFAULT_VALUE_TYPES = [
        ilDclDatatype::INPUTFORMAT_NUMBER,
        ilDclDatatype::INPUTFORMAT_TEXT,
        ilDclDatatype::INPUTFORMAT_BOOLEAN,
        ilDclDatatype::INPUTFORMAT_DATETIME
    ];

    protected $default_value_factory;


    public function __construct(ilDclCreateViewDefinitionGUI $a_parent_obj)
    {
        global $DIC;
        $lng = $DIC['lng'];
        $ilCtrl = $DIC['ilCtrl'];
        parent::__construct($a_parent_obj);

        $this->default_value_factory = new ilDclDefaultValueFactory();
        $this->setId('dcl_tableviews');
        $this->setTitle($lng->txt('dcl_tableview_fieldsettings'));
        $this->addColumn($lng->txt('dcl_tableview_fieldtitle'), null, 'auto');
        $this->addColumn($lng->txt('dcl_tableview_field_access'), null, 'auto');
        $this->addColumn($lng->txt('dcl_tableview_default_value'), null, 'auto');

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
        global $DIC;
        $lng = $DIC['lng'];
        $field = $a_set->getFieldObject();
        $match = $this->default_value_factory->find(intval($field->getDataTypeId()), $a_set->getId());

        /** @var ilDclTextInputGUI $item */
        $item = ilDclCache::getFieldRepresentation($field)->getInputField(new ilPropertyFormGUI());

        if (!is_null($match)) {
            $item->setValue($match->getValue());
        }

        if (!$field->isStandardField()) {
            $this->tpl->setVariable('TEXT_VISIBLE', $lng->txt('dcl_tableview_visible'));
            $this->tpl->setVariable('TEXT_REQUIRED_VISIBLE', $lng->txt('dcl_tableview_required_visible'));
            $this->tpl->setVariable('TEXT_LOCKED_VISIBLE', $lng->txt('dcl_tableview_locked_visible'));
            $this->tpl->setVariable('TEXT_NOT_VISIBLE', $lng->txt('dcl_tableview_not_visible'));
            $this->tpl->setVariable('IS_LOCKED', $a_set->isLockedCreate() ? 'checked' : '');
            $this->tpl->setVariable('IS_REQUIRED', $a_set->isRequiredCreate() ? 'checked' : '');
            $this->tpl->setVariable('DEFAULT_VALUE', $a_set->getDefaultValue());
            $this->tpl->setVariable('IS_VISIBLE', $a_set->isVisibleCreate() ? 'checked' : '');
            $this->tpl->setVariable('IS_NOT_VISIBLE', !$a_set->isVisibleCreate() ? 'checked' : '');
            if (!is_null($item) && in_array($field->getDatatypeId(), self::VALID_DEFAULT_VALUE_TYPES)) {
                $item->setPostVar("default_" . $a_set->getId() . "_" . $field->getDatatypeId());
                $this->tpl->setVariable('INPUT', $item->render());
            }
        } else {
            $this->tpl->setVariable('HIDDEN', 'hidden');
        }

        $this->tpl->setVariable('FIELD_ID', $a_set->getField());
        $this->tpl->setVariable('TITLE', $field->getTitle());
        $this->tpl->parseCurrentBlock();
    }
}