<?php declare(strict_types=1);
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * Class ilChatroomSmileyGUI
 * Provides methods to show, add, edit and delete smilies
 * consisting of icon and keywords
 * @author  Andreas Kordosz <akordosz@databay.de>
 * @version $Id$
 * @ingroup ModulesChatroom
 */
class ilChatroomAdminSmileyGUI extends ilChatroomGUIHandler
{
    protected ?ilPropertyFormGUI $form_gui = null;

    public function executeDefault(string $requestedMethod) : void
    {
        $this->view();
    }

    /**
     * Switches GUI to visible mode and calls editSmiliesObject method
     * which prepares and displays the table of existing smilies.
     */
    public function view() : void
    {
        ilChatroom::checkUserPermissions('read', $this->gui->ref_id);

        $this->gui->switchToVisibleMode();

        self::_checkSetup();

        $this->editSmiliesObject();
    }

    public static function _checkSetup() : bool
    {
        global $DIC;

        $path = self::_getSmileyDir();

        if (!is_dir($path)) {
            ilUtil::sendInfo($DIC->language()->txt('chat_smilies_dir_not_exists'));
            ilUtil::makeDirParents($path);

            if (!is_dir($path)) {
                ilUtil::sendFailure($DIC->language()->txt('chat_smilies_dir_not_available'));
                return false;
            }

            $smilies = [
                'icon_smile.gif',
                'icon_wink.gif',
                'icon_laugh.gif',
                'icon_sad.gif',
                'icon_shocked.gif',
                'icon_tongue.gif',
                'icon_cool.gif',
                'icon_eek.gif',
                'icon_angry.gif',
                'icon_flush.gif',
                'icon_idea.gif',
                'icon_thumbup.gif',
                'icon_thumbdown.gif',
            ];

            foreach ($smilies as $smiley) {
                copy("templates/default/images/emoticons/$smiley", $path . "/$smiley");
            }

            self::_insertDefaultValues();

            ilUtil::sendSuccess($DIC->language()->txt('chat_smilies_initialized'));
        }

        if (!is_writable($path)) {
            ilUtil::sendInfo($DIC->language()->txt('chat_smilies_dir_not_writable'));
        }

        return true;
    }

    public static function _getSmileyDir(bool $withBaseDir = true) : string
    {
        $path = 'chatroom/smilies';

        if ($withBaseDir) {
            $path = ilUtil::getWebspaceDir() . '/' . $path;
        }

        return $path;
    }

    private static function _insertDefaultValues() : void
    {
        $values = [
            ["icon_smile.gif", ":)\n:-)\n:smile:"],
            ["icon_wink.gif", ";)\n;-)\n:wink:"],
            ["icon_laugh.gif", ":D\n:-D\n:laugh:\n:grin:\n:biggrin:"],
            ["icon_sad.gif", ":(\n:-(\n:sad:"],
            ["icon_shocked.gif", ":o\n:-o\n:shocked:"],
            ["icon_tongue.gif", ":p\n:-p\n:tongue:"],
            ["icon_cool.gif", ":cool:"],
            ["icon_eek.gif", ":eek:"],
            ["icon_angry.gif", ":||\n:-||\n:angry:"],
            ["icon_flush.gif", ":flush:"],
            ["icon_idea.gif", ":idea:"],
            ["icon_thumbup.gif", ":thumbup:"],
            ["icon_thumbdown.gif", ":thumbdown:"],
        ];

        foreach ($values as $val) {
            ilChatroomSmilies::_storeSmiley($val[1], $val[0]);
        }
    }

    /**
     * Shows existing smilies table
     * Prepares existing smilies table and displays it.
     */
    public function editSmiliesObject() : void
    {
        if (!$this->rbacsystem->checkAccess('read', $this->gui->ref_id)) {
            $this->ilias->raiseError(
                $this->ilLng->txt('msg_no_perm_read'),
                $this->gui->ilias->error_obj->MESSAGE
            );
        }

        ilChatroomSmilies::_checkSetup();

        if (null === $this->form_gui) {
            $this->form_gui = $this->initSmiliesForm();
        }

        $table = ilChatroomSmiliesGUI::_getExistingSmiliesTable($this->gui);

        $tpl_smilies = new ilTemplate(
            'tpl.chatroom_edit_smilies.html',
            true,
            true,
            'Modules/Chatroom'
        );
        $tpl_smilies->setVariable('SMILEY_TABLE', $table);
        $tpl_smilies->setVariable('SMILEY_FORM', $this->form_gui->getHtml());

        $this->mainTpl->setContent($tpl_smilies->get());
    }

    public function initSmiliesForm() : ilPropertyFormGUI
    {
        global $DIC;

        $this->form_gui = new ilPropertyFormGUI();

        if (isset($this->httpServices->request()->getQueryParams()['_table_nav'])) {
            $this->ilCtrl->setParameter(
                $this->gui,
                '_table_nav',
                $this->httpServices->request()->getQueryParams()['_table_nav']
            );
        }
        $this->form_gui->setFormAction($this->ilCtrl->getFormAction($this->gui, 'smiley-uploadSmileyObject'));

        $sec_l = new ilFormSectionHeaderGUI();

        $sec_l->setTitle($this->ilLng->txt('chatroom_add_smiley'));
        $this->form_gui->addItem($sec_l);

        $inp = new ilImageFileInputGUI(
            $this->ilLng->txt('chatroom_image_path'),
            'chatroom_image_path'
        );
        $inp->setSuffixes(['jpg', 'jpeg', 'png', 'gif', 'svg']);

        $inp->setRequired(true);
        $this->form_gui->addItem($inp);

        $inp = new ilTextAreaInputGUI(
            $this->ilLng->txt('chatroom_smiley_keywords'),
            'chatroom_smiley_keywords'
        );

        $inp->setRequired(true);
        $inp->setUseRte(false);
        $inp->setInfo($this->ilLng->txt('chatroom_smiley_keywords_one_per_line_note'));
        $this->form_gui->addItem($inp);


        if ($this->rbacsystem->checkAccess('write', $this->gui->ref_id)) {
            $this->form_gui->addCommandButton(
                'smiley-uploadSmileyObject',
                $DIC->language()->txt('chatroom_upload_smiley')
            );
        }

        return $this->form_gui;
    }

    /**
     * Shows EditSmileyEntryForm
     * Prepares EditSmileyEntryForm and displays it.
     */
    public function showEditSmileyEntryFormObject() : void
    {
        $this->gui->switchToVisibleMode();

        if (!$this->rbacsystem->checkAccess('read', $this->gui->ref_id)) {
            $this->ilias->raiseError(
                $this->ilLng->txt('msg_no_perm_read'),
                $this->ilias->error_obj->MESSAGE
            );
        }

        $smileyId = $this->refinery->kindlyTo()->int()->transform($this->getRequestValue('smiley_id'));

        if (null === $this->form_gui) {
            $this->form_gui = $this->initSmiliesEditForm($this->getSmileyFormDataById($smileyId));
        }

        $tpl_form = new ilTemplate(
            'tpl.chatroom_edit_smilies.html',
            true,
            true,
            'Modules/Chatroom'
        );

        $tpl_form->setVariable('SMILEY_FORM', $this->form_gui->getHTML());

        $this->mainTpl->setContent($tpl_form->get());
    }

    /**
     * @param $smileyId
     * @return array{chatroom_smiley_id: int, chatroom_smiley_keywords: string, chatroom_current_smiley_image_path: string}
     */
    protected function getSmileyFormDataById(int $smileyId) : array
    {
        $smiley = ilChatroomSmilies::_getSmiley($smileyId);

        $form_data = [
            'chatroom_smiley_id' => $smiley['smiley_id'],
            'chatroom_smiley_keywords' => $smiley['smiley_keywords'],
            'chatroom_current_smiley_image_path' => $smiley['smiley_fullpath'],
        ];

        return $form_data;
    }

    public function initSmiliesEditForm($form_data) : ilPropertyFormGUI
    {
        $this->form_gui = new ilPropertyFormGUI();
        $this->form_gui->setValuesByArray($form_data);

        if (isset($this->httpServices->request()->getQueryParams()['_table_nav'])) {
            $this->ilCtrl->setParameter(
                $this->gui,
                '_table_nav',
                $this->httpServices->request()->getQueryParams()['_table_nav']
            );
        }

        $this->ilCtrl->saveParameter($this->gui, 'smiley_id');
        $this->form_gui->setFormAction($this->ilCtrl->getFormAction($this->gui, 'smiley-updateSmiliesObject'));

        $sec_l = new ilFormSectionHeaderGUI();

        $sec_l->setTitle($this->ilLng->txt('chatroom_edit_smiley'));
        $this->form_gui->addItem($sec_l);

        $inp = new ilChatroomSmiliesCurrentSmileyFormElement(
            $this->ilLng->txt('chatroom_current_smiley_image_path'),
            'chatroom_current_smiley_image_path'
        );

        $inp->setValue($form_data['chatroom_current_smiley_image_path']);
        $this->form_gui->addItem($inp);

        $inp = new ilImageFileInputGUI(
            $this->ilLng->txt('chatroom_image_path'),
            'chatroom_image_path'
        );
        $inp->setSuffixes(['jpg', 'jpeg', 'png', 'gif', 'svg']);

        $inp->setRequired(false);
        $inp->setInfo($this->ilLng->txt('chatroom_smiley_image_only_if_changed'));
        $this->form_gui->addItem($inp);

        $inp = new ilTextAreaInputGUI(
            $this->ilLng->txt('chatroom_smiley_keywords'),
            'chatroom_smiley_keywords'
        );

        $inp->setValue($form_data['chatroom_smiley_keywords']);
        $inp->setUseRte(false);
        $inp->setRequired(true);
        $inp->setInfo($this->ilLng->txt('chatroom_smiley_keywords_one_per_line_note'));
        $this->form_gui->addItem($inp);

        $inp = new ilHiddenInputGUI('chatroom_smiley_id');

        $this->form_gui->addItem($inp);
        $this->form_gui->addCommandButton(
            'smiley-updateSmiliesObject',
            $this->ilLng->txt('submit')
        );
        $this->form_gui->addCommandButton('smiley', $this->ilLng->txt('cancel'));

        return $this->form_gui;
    }

    /**
     * Shows DeleteSmileyForm
     * Prepares DeleteSmileyForm and displays it.
     */
    public function showDeleteSmileyFormObject() : void
    {
        $this->gui->switchToVisibleMode();

        if (!$this->rbacsystem->checkAccess('write', $this->gui->ref_id)) {
            $this->ilias->raiseError(
                $this->ilLng->txt('msg_no_perm_write'),
                $this->ilias->error_obj->MESSAGE
            );
        }

        $smileyId = $this->refinery->kindlyTo()->int()->transform($this->getRequestValue('smiley_id'));

        $smiley = ilChatroomSmilies::_getSmiley($smileyId);

        $confirmation = new ilConfirmationGUI();
        $confirmation->setFormAction($this->ilCtrl->getFormAction($this->gui, 'smiley'));
        $confirmation->setHeaderText($this->ilLng->txt('chatroom_confirm_delete_smiley'));
        $confirmation->addButton($this->ilLng->txt('confirm'), 'smiley-deleteSmileyObject');
        $confirmation->addButton($this->ilLng->txt('cancel'), 'smiley');
        $confirmation->addItem(
            'chatroom_smiley_id',
            $smiley['smiley_id'],
            ilUtil::img($smiley['smiley_fullpath'], $smiley['smiley_keywords']) . ' ' . $smiley['smiley_keywords']
        );

        $this->mainTpl->setContent($confirmation->getHTML());
    }

    /**
     * Deletes a smiley by $_REQUEST['chatroom_smiley_id']
     */
    public function deleteSmileyObject() : void
    {
        if (!$this->rbacsystem->checkAccess('write', $this->gui->ref_id)) {
            $this->ilias->raiseError(
                $this->ilLng->txt('msg_no_perm_write'),
                $this->ilias->error_obj->MESSAGE
            );
        }

        $smileyId = $this->refinery->kindlyTo()->int()->transform($this->getRequestValue('chatroom_smiley_id'));

        ilChatroomSmilies::_deleteSmiley($smileyId);

        $this->ilCtrl->redirect($this->gui, 'smiley');
    }

    /**
     * Updates a smiley and/or its keywords
     * Updates a smiley icon and/or its keywords by $_REQUEST['chatroom_smiley_id']
     * and gets keywords from $_REQUEST['chatroom_smiley_keywords'].
     */
    public function updateSmiliesObject() : void
    {
        if (!$this->rbacsystem->checkAccess('write', $this->gui->ref_id)) {
            $this->ilias->raiseError(
                $this->ilLng->txt('msg_no_perm_write'),
                $this->ilias->error_obj->MESSAGE
            );
        }

        $smileyId = $this->refinery->kindlyTo()->int()->transform($this->getRequestValue('smiley_id'));

        $this->initSmiliesEditForm($this->getSmileyFormDataById($smileyId));

        $keywords = ilChatroomSmilies::_prepareKeywords(ilUtil::stripSlashes(
            $this->refinery->kindlyTo()->string()->transform($this->getRequestValue('chatroom_smiley_keywords'))
        ));

        $atLeastOneKeywordGiven = count($keywords) > 0;

        $isFormValid = $this->form_gui->checkInput();
        if (!$atLeastOneKeywordGiven || !$isFormValid) {
            $errorShown = !$isFormValid;
            if (!$atLeastOneKeywordGiven && !$errorShown) {
                ilUtil::sendFailure($this->ilLng->txt('form_input_not_valid'));
            }

            $this->form_gui->setValuesByPost();

            $this->showEditSmileyEntryFormObject();
            return;
        }

        $data = [];
        $data['smiley_keywords'] = implode("\n", $keywords);
        $data['smiley_id'] = $smileyId;

        if ($this->upload->hasUploads() && !$this->upload->hasBeenProcessed()) {
            $this->upload->process();

            /** @var \ILIAS\FileUpload\DTO\UploadResult $result */
            $result = array_values($this->upload->getResults())[0];
            if ($result && $result->getStatus() == \ILIAS\FileUpload\DTO\ProcessingStatus::OK) {
                $this->upload->moveOneFileTo(
                    $result,
                    ilChatroomSmilies::getSmiliesBasePath(),
                    \ILIAS\FileUpload\Location::WEB,
                    $result->getName(),
                    true
                );

                $data['smiley_path'] = $result->getName();
            }
        }

        ilChatroomSmilies::_updateSmiley($data);

        ilUtil::sendSuccess($this->ilLng->txt('saved_successfully'), true);
        $this->ilCtrl->redirect($this->gui, 'smiley');
    }

    /**
     * Shows confirmation view for deleting multiple smilies
     * Prepares confirmation view for deleting multiple smilies and displays it.
     */
    public function deleteMultipleObject() : void
    {
        $this->gui->switchToVisibleMode();

        if (!$this->rbacsystem->checkAccess('write', $this->gui->ref_id)) {
            $this->ilias->raiseError(
                $this->ilLng->txt('msg_no_perm_write'),
                $this->ilias->error_obj->MESSAGE
            );
        }

        $ids = $this->refinery->kindlyTo()->listOf(
            $this->refinery->kindlyTo()->int()
        )->transform($this->getRequestValue('smiley_id', []));
        if ($ids === []) {
            ilUtil::sendInfo($this->ilLng->txt('select_one'), true);
            $this->ilCtrl->redirect($this->gui, 'smiley');
        }

        $smilies = ilChatroomSmilies::_getSmiliesById($ids);
        if ($smilies === []) {
            ilUtil::sendInfo($this->ilLng->txt('select_one'), true);
            $this->ilCtrl->redirect($this->gui, 'smiley');
        }

        $confirmation = new ilConfirmationGUI();
        $confirmation->setFormAction($this->ilCtrl->getFormAction($this->gui, 'smiley'));
        $confirmation->setHeaderText($this->ilLng->txt('chatroom_confirm_delete_smiley'));
        $confirmation->addButton($this->ilLng->txt('confirm'), 'smiley-confirmedDeleteMultipleObject');
        $confirmation->addButton($this->ilLng->txt('cancel'), 'smiley');

        foreach ($smilies as $s) {
            $confirmation->addItem(
                'sel_ids[]',
                $s['smiley_id'],
                ilUtil::img($s['smiley_fullpath'], $s['smiley_keywords']) . ' ' . $s['smiley_keywords']
            );
        }

        $this->mainTpl->setContent($confirmation->getHTML());
    }

    /**
     * Deletes multiple smilies by $_REQUEST['sel_ids']
     */
    public function confirmedDeleteMultipleObject() : void
    {
        if (!$this->rbacsystem->checkAccess('write', $this->gui->ref_id)) {
            $this->ilias->raiseError(
                $this->ilLng->txt('msg_no_perm_write'),
                $this->ilias->error_obj->MESSAGE
            );
        }

        $ids = $this->refinery->kindlyTo()->listOf(
            $this->refinery->kindlyTo()->int()
        )->transform($this->getRequestValue('sel_ids', []));

        if ($ids === []) {
            $this->ilCtrl->redirect($this->gui, 'smiley');
        }

        ilChatroomSmilies::_deleteMultipleSmilies($ids);

        $this->ilCtrl->redirect($this->gui, 'smiley');
    }

    /**
     * Uploads and stores a new smiley with keywords from
     * $_REQUEST['chatroom_smiley_keywords']
     */
    public function uploadSmileyObject() : void
    {
        if (!$this->rbacsystem->checkAccess('write', $this->gui->ref_id)) {
            $this->ilias->raiseError(
                $this->ilLng->txt('msg_no_perm_write'),
                $this->ilias->error_obj->MESSAGE
            );
        }

        $this->initSmiliesForm();

        $keywords = ilChatroomSmilies::_prepareKeywords(ilUtil::stripSlashes(
            $this->refinery->kindlyTo()->string()->transform($this->getRequestValue('chatroom_smiley_keywords'))
        ));

        $atLeastOneKeywordGiven = count($keywords) > 0;

        $isFormValid = $this->form_gui->checkInput();
        if (!$atLeastOneKeywordGiven || !$isFormValid) {
            $errorShown = !$isFormValid;
            if (!$atLeastOneKeywordGiven && !$errorShown) {
                ilUtil::sendFailure($this->ilLng->txt('form_input_not_valid'));
            }

            $this->form_gui->setValuesByPost();

            $this->view();
            return;
        }

        $pathinfo = pathinfo($_FILES['chatroom_image_path']['name']);
        $target_file = md5(time() . $pathinfo['basename']) . '.' . $pathinfo['extension'];

        if ($this->upload->hasUploads() && !$this->upload->hasBeenProcessed()) {
            $this->upload->process();

            /** @var \ILIAS\FileUpload\DTO\UploadResult $result */
            $result = array_values($this->upload->getResults())[0];
            if ($result && $result->getStatus() == \ILIAS\FileUpload\DTO\ProcessingStatus::OK) {
                $this->upload->moveOneFileTo(
                    $result,
                    ilChatroomSmilies::getSmiliesBasePath(),
                    \ILIAS\FileUpload\Location::WEB,
                    $target_file,
                    true
                );

                ilChatroomSmilies::_storeSmiley(implode("\n", $keywords), $target_file);
            }
        }

        ilUtil::sendSuccess($this->ilLng->txt('saved_successfully'), true);
        $this->ilCtrl->redirect($this->gui, 'smiley');
    }
}
