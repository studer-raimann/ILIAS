<?php

/**
 * Class ilADNNotificationListGUI
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @version 1.0.0
 */
class ilADNNotificationListGUI {

	/**
	 * @var ilTemplate
	 */
	protected $tpl;
	/**
	 * @var ilADNNotificationList
	 */
	protected $list;
	/**
	 * @var ilObjUser
	 */
	protected $usr;
	/**
	 * @var ilSystemNotificationsPlugin
	 */
	protected $pl;


	/**
	 * @param ilADNNotificationList $ilADNNotificationList
	 */
	public function __construct(ilADNNotificationList $ilADNNotificationList) {
		global $DIC;
		$this->pl = ilSystemNotificationsPlugin::getInstance();
		$this->tpl = $this->pl->getTemplate('default/tpl.notification_list.html', false, false);
		$this->list = $ilADNNotificationList;
		$this->usr = $DIC->user();
	}


	/**
	 * @return string
	 */
	public function getHTML() {
		$html = '';
		foreach ($this->list->getActive() as $not) {
			if ($not->isVisibleForUser($this->usr)) {
				$notGUI = new ilADNNotificationGUI($not);
				$html = $notGUI->append($html);
			}
		}
		$this->tpl->setVariable('LIST', $html);

		return $this->tpl->get();
	}
}
