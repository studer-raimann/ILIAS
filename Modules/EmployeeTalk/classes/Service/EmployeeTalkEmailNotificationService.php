<?php
declare(strict_types=1);

namespace ILIAS\EmployeeTalk\Service;

use ilSetting;

final class EmployeeTalkEmailNotificationService
{
    /**
     * @var string $message
     */
    private $message;
    /**
     * @var string $subject
     */
    private $subject;
    /**
     * @var string $to
     */
    private $to;
    /**
     * @var string $cc
     */
    private $cc;
    /**
     * @var VCalender $calendar
     */
    private $calendar;
    /**
     * @var ilSetting $settings
     */
    private $settings;

    /**
     * EmployeeTalkEmailNotificationService constructor.
     * @param string    $message
     * @param string    $subject
     * @param string    $to
     * @param string    $cc
     * @param VCalender $calendar
     */
    public function __construct(string $message, string $subject, string $to, string $cc, VCalender $calendar)
    {
        global $DIC;

        $this->message = $message;
        $this->subject = $subject;
        $this->to = $to;
        $this->cc = $cc;
        $this->calendar = $calendar;

        $this->settings = $DIC->settings();
    }

    /**
     * Send the notification
     *
     * @return bool
     */
    public function send(): bool {
        global $DIC;

        $language = $DIC->language();
        /** @var \ilMailMimeSenderFactory $senderFactory */
        $senderFactory = $DIC["mail.mime.sender.factory"];
        $sender        = $senderFactory->system();

        $mime_boundary = "b1_" . md5(strval(time()));

        $from = $sender->getFromAddress();
        $cc = $this->cc;
        $replayTo = $sender->getReplyToAddress();
        $headers = "From: $from <$from>\n";
        $headers .= "Cc: $cc <$cc>\n";
        $headers .= "Reply-To: $replayTo <$replayTo>\n";
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-Type: multipart/mixed; boundary=\"$mime_boundary\"\n";
        $headers .= "Content-class: urn:content-classes:calendarmessage\n";

        $subjectPrefix = strval($this->settings->get('mail_subject_prefix'));
        $subjectDetails = $this->subject;
        $allowExternalMails = boolval(intval($this->settings->get('mail_allow_external')));

        $mailsent = false;
        $subject =  $language->txt('notification_talks_subject');
        if($allowExternalMails) {
            $mailsent = mail($this->to, "$subjectPrefix $subject: $subjectDetails", $this->getMessage($mime_boundary), $headers);
        }

        return $mailsent;
    }

    private function getMessage(string $mimeBoundary): string {
        $message = "--$mimeBoundary\r\n";
        $childBoundary = "b2_" . md5(strval(time()));
        $message .= "Content-Type: multipart/alternative; boundary=\"$childBoundary\"\r\n\r\n";
        $message .= $this->getTextMessage($childBoundary);
        $message .= $this->getHtmlMessage($childBoundary);
        $message .= "--$childBoundary--\r\n\r\n";
        $message .= $this->getIcalEvent($mimeBoundary);
        $message .= "--$mimeBoundary--";

        return $message;
    }

    private function getHtmlMessage(string $mime_boundary): string {
        $message = "--$mime_boundary\r\n";
        $message .= "Content-Type: text/html; charset=\"utf-8\"\r\n";
        $message .= "Content-Transfer-Encoding: QUOTED-PRINTABLE\r\n\r\n";
        $message .= "<html>\r\n";
        $message .= "<body>\r\n";
        $message .= nl2br($this->message);
        $message .= "\r\n</body>\r\n";
        $message .= "</html>\r\n";

        return $message;
    }

    private function getTextMessage(string $mime_boundary): string {
        $message = "--$mime_boundary\r\n";
        $message .= "Content-Type: text/plain; charset=\"utf-8\"\r\n";
        $message .= "Content-Transfer-Encoding: QUOTED-PRINTABLE\r\n\r\n";
        $message .= $this->message . "\r\n";

        return $message;
    }

    private function getIcalEvent(string $mime_boundary): string
    {
        $message = "--$mime_boundary\r\n";
        $message .= 'Content-Type: text/calendar;name="appointment.ics";method=' . $this->calendar->getMethod()."\r\n";
        $message .= "Content-Disposition: attachment;filename=\"appointment.ics\"\r\n";
        $message .= "Content-Transfer-Encoding: QUOTED-PRINTABLE\r\n\r\n";
        $message .= $this->calendar->render() . "\r\n";
        return $message;
    }
}
