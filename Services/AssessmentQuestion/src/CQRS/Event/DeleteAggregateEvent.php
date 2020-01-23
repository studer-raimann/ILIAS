<?php

namespace ILIAS\AssessmentQuestion\DomainModel\Event;

use ILIAS\AssessmentQuestion\CQRS\Event\AbstractIliasObjectDomainEvent;
use ILIAS\AssessmentQuestion\DomainModel\Answer\Answer;

/**
 * Class QuestionAnswerAddedEvent
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class DeleteAggregateEvent extends AbstractIliasObjectDomainEvent {
    
    public const NAME = 'DeleteAggregateEvent';    
    /**
     * @return string
     *
     * Add a Constant EVENT_NAME to your class: Name it: [aggregate].[event]
     * e.g. 'question.created'
     */
    public function getEventName(): string {
        return self::NAME;
    }
 
    public function restoreEventBody(string $json_data) {
        //no event content
    }
}