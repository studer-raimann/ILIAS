<?php
declare(strict_types=1);

namespace ILIAS\Services\AssessmentQuestion\PublicApi\Authoring;

use ILIAS\AssessmentQuestion\UserInterface\Web\Component\QuestionComponent;
use ILIAS\UI\Component\Layout\Page\Page;
use srag\CQRS\Aggregate\DomainObjectId;

/**
 * Class AuthoringService
 *
 * @package ILIAS\Services\AssessmentQuestion\PublicApi\Authoring
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class AuthoringService
{

    /**
     * @var int
     */
    protected $container_obj_id;
    /**
     * @var int
     */
    protected $actor_user_id;
    /**
     * @var string
     */
    protected $lng_key;

    /**
     * @param int $container_obj_id
     * @param int $actor_user_id
     */
    public function __construct(int $container_obj_id, int $actor_user_id)
    {
        global $DIC;

        $this->container_obj_id = $container_obj_id;
        $this->actor_user_id = $actor_user_id;
    }


    /**
     * @param DomainObjectId                            $question_uuid
     *
     * @return AuthoringQuestion
     */
    public function question() : AuthoringQuestion
    {
        return new AuthoringQuestion();
    }

    /**
     * @return AuthoringQuestionList
     */
    public function questionList() : AuthoringQuestionList
    {
        return new AuthoringQuestionList($this->container_obj_id, $this->actor_user_id);
    }

    public function getGenericFeedbackPageGUI(Page $page) : \ilAsqGenericFeedbackPageGUI
    {
        return new \ilAsqGenericFeedbackPageGUI($page);
    }
}
