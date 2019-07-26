<?php

namespace ILIAS\Services\AssessmentQuestion\PublicApi\Contracts;

use ILIAS\Services\AssessmentQuestion\PublicApi\Contracts\QuestionIdContract;
use ILIAS\Services\AssessmentQuestion\PublicApi\Contracts\RevisionIdContract;

/**
 * Interface AuthoringQueryServiceContract
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 *
 * @package ILIAS\Services\AssessmentQuestion\PublicApi\Contracts
 */
interface QueryServiceContract {

	/**
	 * @param int $containerId
	 *
	 * @return array
	 *
	 * Gets all questions of a Container from db as an Array containing
	 * the generic question data fields
	 */
	public function GetQuestionsOfContainerAsAssocArray(int $containerId): array;


	/**
	 * @param int $container_id
	 *
	 * @return QuestionDtoContract[]
	 */
	public function GetQuestionsOfContainerAsDtoList(int $container_id): array;


	/**
	 * @param string $questionUuid
	 *
	 * @return string
	 */
	public function getQuestionQtiXml(QuestionIdContract $questionUuid, RevisionIdContract $revisionUuid = null): string;
}