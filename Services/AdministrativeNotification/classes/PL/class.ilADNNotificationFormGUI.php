<?php

/**
 * Class ilADNNotificationFormGUI
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @version 1.0.0
 */
class ilADNNotificationFormGUI extends ilPropertyFormGUI {

	const F_TITLE = 'title';
	const F_BODY = 'body';
	const F_TYPE = 'type';
	const F_TYPE_DURING_EVENT = 'type_during_event';
	const F_EVENT_DATE = 'event_date';
	const F_DISPLAY_DATE = 'display_date';
	const F_PERMANENT = 'permanent';
	const F_POSITION = 'position';
	const F_ADDITIONAL_CLASSES = 'additional_classes';
	const F_PREVENT_LOGIN = 'prevent_login';
	const F_INTERRUPTIVE = 'interruptive';
	const F_ALLOWED_USERS = 'allowed_users';
	const F_DISMISSABLE = 'dismissable';
	const F_LIMIT_TO_ROLES = 'limit_to_roles';
	const F_LIMITED_TO_ROLE_IDS = 'limited_to_role_ids';
	/**
	 * @var ilADNNotification
	 */
	protected $ilADNNotification;
	/**
	 * @var array
	 */
	protected static $tags = array(
		'a',
		'strong',
		'ol',
		'ul',
		'li',
		'p',
	);
	/**
	 * @var bool
	 */
	protected $is_new;
	/**
	 * @var ilSystemNotificationsPlugin
	 */
	protected $pl;
	/**
	 * @var ilCtrl
	 */
	protected $ctrl;


	/**
	 * @param ilADNNotificationGUI $parent_gui
	 * @param ilADNNotification    $ilADNNotification
	 */
	public function __construct($parent_gui, ilADNNotification $ilADNNotification) {
		parent::__construct();
		global $DIC;
		$this->ctrl = $DIC->ctrl();
		$this->ilADNNotification = $ilADNNotification;
		$this->pl = ilSystemNotificationsPlugin::getInstance();
		//		$this->pl->updateLanguageFiles();
		$this->is_new = $ilADNNotification->getId() == 0;
		$this->setFormAction($this->ctrl->getFormAction($parent_gui));
		$this->initForm();
	}


	/**
	 * @param string $var
	 *
	 * @return string
	 */
	protected function txt($var) {
		return $this->pl->txt('msg_' . $var);
	}


	/**
	 * @param string $var
	 *
	 * @return string
	 */
	protected function infoTxt($var) {
		return $this->pl->txt('msg_' . $var . '_info');
	}


	public function initForm() {
		$this->setTitle($this->txt('form_title'));

		$type = new ilSelectInputGUI($this->txt(self::F_TYPE), self::F_TYPE);
		$type->setOptions(array(
			ilADNNotification::TYPE_INFO => $this->txt(self::F_TYPE . '_' . ilADNNotification::TYPE_INFO),
			ilADNNotification::TYPE_WARNING => $this->txt(self::F_TYPE . '_' . ilADNNotification::TYPE_WARNING),
			ilADNNotification::TYPE_ERROR => $this->txt(self::F_TYPE . '_' . ilADNNotification::TYPE_ERROR),

		));
		$this->addItem($type);

		$title = new ilTextInputGUI($this->txt(self::F_TITLE), self::F_TITLE);
		$this->addItem($title);

		$body = new ilTextAreaInputGUI($this->txt(self::F_BODY), self::F_BODY);
		$body->setUseRte(true);
		$body->setRteTags(self::$tags);
		$this->addItem($body);

		$permanent = new ilRadioGroupInputGUI($this->txt(self::F_PERMANENT), self::F_PERMANENT);

		$permanent_yes = new ilRadioOption($this->txt(self::F_PERMANENT . '_yes'), 1);
		$permanent->addOption($permanent_yes);
		$this->addItem($permanent);

		$dismissable = new ilCheckboxInputGUI($this->txt(self::F_DISMISSABLE), self::F_DISMISSABLE);
		$dismissable->setInfo($this->infoTxt(self::F_DISMISSABLE));
		$this->addItem($dismissable);

		$limit_to_roles = new ilCheckboxInputGUI($this->txt(self::F_LIMIT_TO_ROLES), self::F_LIMIT_TO_ROLES);
		$limited_to_role_ids = new ilMultiSelectInputGUI($this->txt(self::F_LIMITED_TO_ROLE_IDS), self::F_LIMITED_TO_ROLE_IDS);
		$limited_to_role_ids->setOptions(self::getRoles(ilRbacReview::FILTER_ALL_GLOBAL));
		$limit_to_roles->addSubItem($limited_to_role_ids);
		$limit_to_roles->setInfo($this->infoTxt(self::F_LIMIT_TO_ROLES));
		$this->addItem($limit_to_roles);

		$permanent_no = new ilRadioOption($this->txt(self::F_PERMANENT . '_no'), 0);
		$display_time = new ilDateDurationInputGUI($this->txt(self::F_DISPLAY_DATE), self::F_DISPLAY_DATE);
		$display_time->setShowTime(true);
		$display_time->setMinuteStepSize(1);
		$permanent_no->addSubItem($display_time);
		$event_time = new ilDateDurationInputGUI($this->txt(self::F_EVENT_DATE), self::F_EVENT_DATE);
		$event_time->setShowTime(true);
		$event_time->setMinuteStepSize(1);
		$permanent_no->addSubItem($event_time);
		$type_during_event = new ilSelectInputGUI($this->txt(self::F_TYPE_DURING_EVENT), self::F_TYPE_DURING_EVENT);
		$type_during_event->setOptions(array(
			ilADNNotification::TYPE_INFO => $this->txt(self::F_TYPE . '_' . ilADNNotification::TYPE_INFO),
			ilADNNotification::TYPE_WARNING => $this->txt(self::F_TYPE . '_' . ilADNNotification::TYPE_WARNING),
			ilADNNotification::TYPE_ERROR => $this->txt(self::F_TYPE . '_' . ilADNNotification::TYPE_ERROR),

		));
		$permanent_no->addSubItem($type_during_event);

		$permanent->addOption($permanent_no);

		$position = new ilSelectInputGUI($this->txt(self::F_POSITION), self::F_POSITION);
		$position->setOptions(array(
			ilADNNotification::POS_TOP => $this->txt(self::F_POSITION . '_' . ilADNNotification::POS_TOP),
			ilADNNotification::POST_LEFT => $this->txt(self::F_POSITION . '_' . ilADNNotification::POST_LEFT),
			ilADNNotification::POS_RIGHT => $this->txt(self::F_POSITION . '_' . ilADNNotification::POS_RIGHT),
			ilADNNotification::POS_BOTTOM => $this->txt(self::F_POSITION . '_' . ilADNNotification::POS_BOTTOM),
		));
		// $this->addItem($position);

		$additional_classes = new ilTextInputGUI($this->txt(self::F_ADDITIONAL_CLASSES), self::F_ADDITIONAL_CLASSES);
		$this->addItem($additional_classes);

		$prevent_login = new ilCheckboxInputGUI($this->txt(self::F_PREVENT_LOGIN), self::F_PREVENT_LOGIN);
		$prevent_login->setInfo($this->infoTxt(self::F_PREVENT_LOGIN));
		$allowed_users = new ilTextInputGUI($this->txt(self::F_ALLOWED_USERS), self::F_ALLOWED_USERS);

		$prevent_login->addSubItem($allowed_users);

		$this->addItem($prevent_login);

		$interruptive = new ilCheckboxInputGUI($this->txt(self::F_INTERRUPTIVE), self::F_INTERRUPTIVE);
		$interruptive->setInfo($this->infoTxt(self::F_INTERRUPTIVE));
		$this->addItem($interruptive);

		$this->addButtons();
	}


	public function fillForm() {
		$array = array(
			self::F_TITLE => $this->ilADNNotification->getTitle(),
			self::F_BODY => $this->ilADNNotification->getBody(),
			self::F_TYPE => $this->ilADNNotification->getType(),
			self::F_TYPE_DURING_EVENT => $this->ilADNNotification->getTypeDuringEvent(),
			self::F_PERMANENT => (int)$this->ilADNNotification->getPermanent(),
			self::F_POSITION => $this->ilADNNotification->getPosition(),
			self::F_ADDITIONAL_CLASSES => $this->ilADNNotification->getAdditionalClasses(),
			self::F_PREVENT_LOGIN => $this->ilADNNotification->getPreventLogin(),
			self::F_ALLOWED_USERS => @implode(',', $this->ilADNNotification->getAllowedUsers()),
			self::F_DISMISSABLE => $this->ilADNNotification->getDismissable(),
			self::F_LIMIT_TO_ROLES => $this->ilADNNotification->isLimitToRoles(),
			self::F_LIMITED_TO_ROLE_IDS => $this->ilADNNotification->getLimitedToRoleIds(),
			self::F_INTERRUPTIVE => $this->ilADNNotification->isInterruptive(),
		);
		$this->setValuesByArray($array);
		/**
		 * @var ilDateDurationInputGUI $f_event_date
		 * @var ilDateDurationInputGUI $f_display_date
		 */
		if ($eventStart = $this->ilADNNotification->getEventStart()) {
			$f_event_date = $this->getItemByPostVar(self::F_EVENT_DATE);
			$f_event_date->setStart(new ilDateTime($eventStart, IL_CAL_UNIX));
			$f_event_date->setEnd(new ilDateTime($this->ilADNNotification->getEventEnd(), IL_CAL_UNIX));
		}

		if ($displayStart = $this->ilADNNotification->getDisplayStart()) {
			$f_display_date = $this->getItemByPostVar(self::F_DISPLAY_DATE);
			$f_display_date->setStart(new ilDateTime($displayStart, IL_CAL_UNIX));
			$f_display_date->setEnd(new ilDateTime($this->ilADNNotification->getDisplayEnd(), IL_CAL_UNIX));
		}
	}


	/**
	 * @return bool
	 */
	protected function fillObject() {
		if (!$this->checkInput()) {
			return false;
		}

		$this->ilADNNotification->setTitle($this->getInput(self::F_TITLE));
		$this->ilADNNotification->setBody($this->getInput(self::F_BODY));
		$this->ilADNNotification->setType($this->getInput(self::F_TYPE));
		$this->ilADNNotification->setTypeDuringEvent($this->getInput(self::F_TYPE_DURING_EVENT));
		$this->ilADNNotification->setPermanent($this->getInput(self::F_PERMANENT));
		$this->ilADNNotification->setPosition($this->getInput(self::F_POSITION));
		$this->ilADNNotification->setAdditionalClasses($this->getInput(self::F_ADDITIONAL_CLASSES));
		$this->ilADNNotification->setPreventLogin($this->getInput(self::F_PREVENT_LOGIN));
		$this->ilADNNotification->setAllowedUsers(@explode(',', $this->getInput(self::F_ALLOWED_USERS)));
		$this->ilADNNotification->setDismissable($this->getInput(self::F_DISMISSABLE));
		$this->ilADNNotification->setLimitToRoles($this->getInput(self::F_LIMIT_TO_ROLES));
		$this->ilADNNotification->setLimitedToRoleIds($this->getInput(self::F_LIMITED_TO_ROLE_IDS));
		$this->ilADNNotification->setInterruptive($this->getInput(self::F_INTERRUPTIVE));

		/**
		 * @var ilDateDurationInputGUI $f_event_date
		 * @var ilDateDurationInputGUI $f_display_date
		 */
		$f_event_date = $this->getItemByPostVar(self::F_EVENT_DATE);
		if ($f_event_date->getStart() instanceof ilDateTime) {
			$this->ilADNNotification->setEventStart($f_event_date->getStart()->get(IL_CAL_UNIX));
		}
		if ($f_event_date->getEnd() instanceof ilDateTime) {
			$this->ilADNNotification->setEventEnd($f_event_date->getEnd()->get(IL_CAL_UNIX));
		}

		$f_display_date = $this->getItemByPostVar(self::F_DISPLAY_DATE);
		if ($f_display_date->getStart() instanceof ilDateTime) {
			$this->ilADNNotification->setDisplayStart($f_display_date->getStart()->get(IL_CAL_UNIX));
		}
		if ($f_display_date->getEnd() instanceof ilDateTime) {
			$this->ilADNNotification->setDisplayEnd($f_display_date->getEnd()->get(IL_CAL_UNIX));
		}

		return true;
	}


	/**
	 * @param ilDateTime $ilDate_start
	 * @param ilDateTime $ilDate_end
	 *
	 * @return array
	 */
	public function getDateArray(ilDateTime $ilDate_start, ilDateTime $ilDate_end) {
		$return = array();
		$timestamp = $ilDate_start->get(IL_CAL_UNIX);
		$return['start']['d'] = date('d', $timestamp);
		$return['start']['m'] = date('m', $timestamp);
		$return['start']['y'] = date('Y', $timestamp);
		$timestamp = $ilDate_end->get(IL_CAL_UNIX);
		$return['end']['d'] = date('d', $timestamp);
		$return['end']['m'] = date('m', $timestamp);
		$return['end']['y'] = date('Y', $timestamp);

		return $return;
	}


	/**
	 * @return bool false when unsuccessful or int request_id when successful
	 */
	public function saveObject() {
		if (!$this->fillObject()) {
			return false;
		}
		if ($this->ilADNNotification->getId() > 0) {
			$this->ilADNNotification->update();
		} else {
			$this->ilADNNotification->create();
		}

		return $this->ilADNNotification->getId();
	}


	protected function addButtons() {
		if ($this->is_new) {
			$this->addCommandButton(ilSystemNotificationsConfigGUI::CMD_SAVE, $this->txt('form_button_' . ilSystemNotificationsConfigGUI::CMD_SAVE));
		} else {
			$this->addCommandButton(ilSystemNotificationsConfigGUI::CMD_UPDATE, $this->txt('form_button_'
				. ilSystemNotificationsConfigGUI::CMD_UPDATE));
			$this->addCommandButton(ilSystemNotificationsConfigGUI::CMD_UPDATE_AND_STAY, $this->txt('form_button_'
				. ilSystemNotificationsConfigGUI::CMD_UPDATE_AND_STAY));
		}
		$this->addCommandButton(ilSystemNotificationsConfigGUI::CMD_CANCEL, $this->txt('form_button_' . ilSystemNotificationsConfigGUI::CMD_CANCEL));
	}


	/**
	 * @param int  $filter
	 * @param bool $with_text
	 *
	 * @return array
	 */
	public static function getRoles($filter, $with_text = true) {
		global $DIC;
		$opt = array( 0 => 'Login' );
		$role_ids = array( 0 );
		foreach ($DIC->rbac()->review()->getRolesByFilter($filter) as $role) {
			$opt[$role['obj_id']] = $role['title'] . ' (' . $role['obj_id'] . ')';
			$role_ids[] = $role['obj_id'];
		}
		if ($with_text) {
			return $opt;
		} else {
			return $role_ids;
		}
	}
}
