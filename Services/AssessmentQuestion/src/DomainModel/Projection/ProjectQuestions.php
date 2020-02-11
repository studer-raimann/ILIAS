<?php

namespace ILIAS\AssessmentQuestion\DomainModel\Projection;



use ILIAS\AssessmentQuestion\DomainModel\Question;
use ILIAS\AssessmentQuestion\Infrastructure\Persistence\Projection\PublishedQuestionRepository;
use srag\CQRS\Aggregate\AggregateRoot;

/**
 * Class ProjectQuestions
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class ProjectQuestions {

    /**
     * @param AggregateRoot $projectee
     *
     * @return mixed|void
     */
	public function project(AggregateRoot $projectee) {
	    /** @var Question $projectee */
		$repository = new PublishedQuestionRepository();
        $repository->saveNewQuestionRevision($projectee);
	}
}