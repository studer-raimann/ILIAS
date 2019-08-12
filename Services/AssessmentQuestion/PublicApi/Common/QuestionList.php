<?php
declare(strict_types=1);

namespace ILIAS\Services\AssessmentQuestion\PublicApi\Common;

/**
 * Class AuthoringQueryService
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 *
 * @package ILIAS\Services\AssessmentQuestion\PublicApi
 */
interface QuestionList
{

    /**
     * @param int $container_id
     *
     * @return array
     */
    public function getQuestionsOfContainerAsAssocArray() : array;


    /**
     * @param int $container_id
     *
     * @return QuestionDto[]
     */
    public function getQuestionsOfContainerAsDtoList() : array;
}