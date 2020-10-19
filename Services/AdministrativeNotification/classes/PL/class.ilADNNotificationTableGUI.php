<?php

/**
 * Class ilADNNotificationTableGUI
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @version 1.0.0
 */
class ilADNNotificationTableGUI extends ilTable2GUI {

	/**
	 * @var ilSystemNotificationsPlugin
	 */
	protected $pl;
	/**
	 * @var ilCtrl
	 */
	protected $ctrl;


	/**
	 * @param ilSystemNotificationsConfigGUI $a_parent_obj
	 * @param string                         $a_parent_cmd
	 */
	public function __construct(ilSystemNotificationsConfigGUI $a_parent_obj, $a_parent_cmd) {
		global $DIC;
		$this->ctrl = $DIC->ctrl();
		$this->pl = ilSystemNotificationsPlugin::getInstance();
		//		$this->pl->updateLanguageFiles();
		$this->setId('msg_msg_table');
		parent::__construct($a_parent_obj, $a_parent_cmd);
		$this->setRowTemplate('tpl.row.html', $this->pl->getDirectory());
		$this->setTitle($this->pl->txt('msg_table_title'));
		$this->setFormAction($this->ctrl->getFormAction($this->parent_obj));
		//
		// Columns
		$this->addColumn($this->pl->txt('msg_title'));
		$this->addColumn($this->pl->txt('msg_type'));
		$this->addColumn($this->pl->txt('msg_type_during_event'));
		$this->addColumn($this->pl->txt('msg_event_start', 'event_start_unix'));
		$this->addColumn($this->pl->txt('msg_event_end', 'event_end_unix'));
		$this->addColumn($this->pl->txt('msg_display_start', 'display_start_unix'));
		$this->addColumn($this->pl->txt('msg_display_end', 'display_end_unix'));
		$this->addColumn($this->pl->txt('common_actions'));
		// ...
		/*$button = ilLinkButton::getInstance();
		$button->setCaption($this->pl->txt('usr_table_button_select_mem'), false);
		$button->setUrl("#");
		$button->setId("select_mem");
		$this->toolbar->addButtonInstance($button);*/
		/*$button = ilLinkButton::getInstance();
		$button->setCaption($this->pl->txt('usr_table_button_select_tut'), false);
		$button->setUrl("#");
		$button->setId("select_tut");*/
		/*$this->toolbar->addButtonInstance($button);
		$button = ilLinkButton::getInstance();
		$button->setCaption($this->pl->txt('usr_table_button_select_adm'), false);
		$button->setUrl("#");
		$button->setId("select_adm");
		$this->toolbar->addButtonInstance($button);*/

		$this->initData();
	}


	protected function initData() {
		$ilADNNotificationList = ilADNNotification::getCollection();
		$ilADNNotificationList->dateFormat();
		$this->setData($ilADNNotificationList->getArray());
	}


	protected function fillRow($a_set) {
		/**
		 * @var ilADNNotification $ilADNNotification
		 */
		$ilADNNotification = ilADNNotification::find($a_set['id']);
		$this->tpl->setVariable('TITLE', $ilADNNotification->getTitle());
		$this->tpl->setVariable('TYPE', $this->pl->txt('msg_type_' . $ilADNNotification->getType()));
		$this->tpl->setVariable('TYPE_DURING_EVENT', $this->pl->txt('msg_type_' . $ilADNNotification->getTypeDuringEvent()));

		if (!$ilADNNotification->getPermanent()) {
			$this->tpl->setVariable('EVENT_START', $a_set['event_start']);
			$this->tpl->setVariable('EVENT_END', $a_set['event_end']);
			$this->tpl->setVariable('DISPLAY_START', $a_set['display_start']);
			$this->tpl->setVariable('DISPLAY_END', $a_set['display_end']);
		}

		$this->ctrl->setParameter($this->parent_obj, ilSystemNotificationsConfigGUI::NOT_MSG_ID, $ilADNNotification->getId());
		$actions = new ilAdvancedSelectionListGUI();
		$actions->setListTitle($this->pl->txt('common_actions'));
		$actions->setId('msg_' . $ilADNNotification->getId());
		$actions->addItem($this->lng->txt('edit'), '', $this->ctrl->getLinkTarget($this->parent_obj, ilSystemNotificationsConfigGUI::CMD_EDIT));
		$actions->addItem($this->lng->txt('delete'), '', $this->ctrl->getLinkTarget($this->parent_obj, ilSystemNotificationsConfigGUI::CMD_CONFIRM_DELETE));
		if ($ilADNNotification->getDismissable()) {
			$actions->addItem($this->pl->txt('msg_reset_dismiss'), '', $this->ctrl->getLinkTarget($this->parent_obj, ilSystemNotificationsConfigGUI::CMD_RESET_FOR_ALL));
		}
		$this->tpl->setVariable('ACTIONS', $actions->getHTML());
	}
}
