<?php

use ILIAS\MyStaff\ilMyStaffAccess;
use ILIAS\EmployeeTalk\UI\ControlFlowCommand;
use ILIAS\Modules\EmployeeTalk\Talk\Repository\IliasDBEmployeeTalkRepository;

/**
 * Class ilMStShowUserGUI
 *
 * @author            Martin Studer <ms@studer-raimann.ch>
 *
 * @ilCtrl_IsCalledBy ilMStShowUserGUI: ilMyStaffGUI
 * @ilCtrl_Calls ilMStShowUserGUI: ilUserCertificateGUI
 */
class ilMStShowUserGUI
{
    const CMD_INDEX = 'index';
    const CMD_SHOW_USER = 'showUser';

    const TAB_SHOW_USER = 'show_user';
    const TAB_SHOW_COURSES = 'show_courses';
    const TAB_SHOW_CERTIFICATES = 'show_certificates';
    const TAB_SHOW_COMPETENCES = 'show_competences';
    const TAB_SHOW_TALKS = 'show_talks';

    /**
     * @var int
     */
    protected $usr_id;
    /**
     * @var ilMyStaffAccess
     */
    protected $access;


    /**
     *
     */
    public function __construct()
    {
        global $DIC;

        $this->access = ilMyStaffAccess::getInstance();

        $this->usr_id = $DIC->http()->request()->getQueryParams()['usr_id'];
        $DIC->ctrl()->setParameter($this, 'usr_id', $this->usr_id);

        $DIC->ui()->mainTemplate()->setTitle(ilUserUtil::getNamePresentation($this->usr_id));
        $DIC->ui()->mainTemplate()->setTitleIcon(ilObjUser::_getPersonalPicturePath($this->usr_id, "xxsmall"));
    }


    /**
     *
     */
    protected function checkAccessOrFail()
    {
        global $DIC;

        if (!$this->usr_id) {
            ilUtil::sendFailure($DIC->language()->txt("permission_denied"), true);
            $DIC->ctrl()->redirectByClass(ilDashboardGUI::class, "");
        }

        if ($this->access->hasCurrentUserAccessToMyStaff()
            && $this->access->hasCurrentUserAccessToUser($this->usr_id)) {
            return;
        } else {
            ilUtil::sendFailure($DIC->language()->txt("permission_denied"), true);
            $DIC->ctrl()->redirectByClass(ilDashboardGUI::class, "");
        }
    }


    /**
     *
     */
    public function executeCommand()
    {
        global $DIC;

        $this->checkAccessOrFail();

        $cmd = $DIC->ctrl()->getCmd();
        $next_class = $DIC->ctrl()->getNextClass();

        switch ($next_class) {
            case strtolower(ilMStShowUserCoursesGUI::class):
                $this->addTabs(self::TAB_SHOW_COURSES);
                $gui = new ilMStShowUserCoursesGUI();
                $DIC->ctrl()->forwardCommand($gui);
                break;
            case strtolower(ilUserCertificateGUI::class):
                $this->addTabs(self::TAB_SHOW_CERTIFICATES);
                $gui = new ilUserCertificateGUI(
                    null,
                    null,
                    null,
                    new ilObjUser($this->usr_id)
                );
                $DIC->ctrl()->forwardCommand($gui);
                break;
            case strtolower(ilMStShowUserCompetencesGUI::class):
                $this->addTabs(self::TAB_SHOW_COMPETENCES);
                $gui = new ilMStShowUserCompetencesGUI($DIC);
                $DIC->ctrl()->forwardCommand($gui);
                break;
            case strtolower(ilEmployeeTalkMyStaffUserGUI::class):
                $this->addTabs(self::TAB_SHOW_TALKS);
                $gui = new ilEmployeeTalkMyStaffUserGUI(
                    ilMyStaffAccess::getInstance(),
                    $DIC->ctrl(),
                    $DIC->language(),
                    $DIC->http()->request(),
                    $DIC->ui()->mainTemplate(),
                    $DIC->tabs(),
                    new IliasDBEmployeeTalkRepository($DIC->database()),
                    $DIC->ui()
                );
                $DIC->ctrl()->forwardCommand($gui);
                break;
            default:

                switch ($cmd) {
                    case self::CMD_SHOW_USER:
                        $this->addTabs(self::TAB_SHOW_USER);
                        $this->$cmd();
                        break;
                    default:
                        $this->index();
                        break;
                }
        }
    }


    /**
     *
     */
    protected function index()
    {
        global $DIC;
        $DIC->ctrl()->redirectByClass(ilMStShowUserCoursesGUI::class);
    }


    /**
     *
     */
    protected function showUser()
    {
        global $DIC;

        //Redirect if Profile is not public
        $user = new ilObjUser($this->usr_id);
        if (!$user->hasPublicProfile()) {
            $DIC->ctrl()->redirectByClass(self::class, self::CMD_INDEX);
        }

        $pub_profile = new ilPublicUserProfileGUI($this->usr_id);
        $DIC->ui()->mainTemplate()->setContent($pub_profile->getEmbeddable());
    }


    /**
     *
     */
    public function cancel()
    {
        global $DIC;

        $DIC->ctrl()->redirect($this);
    }


    /**
     * @param string $active_tab_id
     */
    protected function addTabs($active_tab_id)
    {
        global $DIC;

        $DIC->tabs()->setBackTarget($DIC->language()->txt('mst_list_users'), $DIC->ctrl()->getLinkTargetByClass(array(
            ilMyStaffGUI::class,
            self::class,
            ilMStListUsersGUI::class,
        )));

        if ($this->access->hasCurrentUserAccessToMyStaff()) {
            $DIC->tabs()->addTab(self::TAB_SHOW_COURSES, $DIC->language()->txt('mst_list_courses'), $DIC->ctrl()->getLinkTargetByClass(array(
                ilMyStaffGUI::class,
                self::class,
                ilMStShowUserCoursesGUI::class,
            )));
        }

        if ($this->access->hasCurrentUserAccessToCertificates()) {
            $DIC->tabs()->addTab(self::TAB_SHOW_CERTIFICATES, $DIC->language()->txt('mst_list_certificates'), $DIC->ctrl()->getLinkTargetByClass(array(
                ilMyStaffGUI::class,
                self::class,
                ilUserCertificateGUI::class,
            )));
        }

        if ($this->access->hasCurrentUserAccessToCompetences()) {
            $DIC->tabs()->addTab(self::TAB_SHOW_COMPETENCES, $DIC->language()->txt('mst_list_competences'), $DIC->ctrl()->getLinkTargetByClass(array(
                ilMyStaffGUI::class,
                self::class,
                ilMStShowUserCompetencesGUI::class,
            )));
        }


        $user = new ilObjUser($this->usr_id);
        if ($user->hasPublicProfile()) {
            $DIC->ctrl()->setParameterByClass(self::class, 'usr_id', $this->usr_id);
            $public_profile_url = $DIC->ctrl()->getLinkTargetByClass(self::class, self::CMD_SHOW_USER);
            $DIC->tabs()->addTab(self::TAB_SHOW_USER, $DIC->language()->txt('public_profile'), $public_profile_url);
        }

        if ($this->access->hasCurrentUserAccessToMyStaff()) {
            $DIC->ctrl()->setParameterByClass(strtolower(self::class), 'usr_id', $this->usr_id);
            $DIC->tabs()->addTab(self::TAB_SHOW_TALKS, $DIC->language()->txt('etal_talks'), $DIC->ctrl()->getLinkTargetByClass([
                strtolower(ilMyStaffGUI::class),
                strtolower(self::class),
                strtolower(ilEmployeeTalkMyStaffUserGUI::class)
            ], ControlFlowCommand::INDEX));
        }

        if ($active_tab_id) {
            $DIC->tabs()->activateTab($active_tab_id);
        }
    }
}
