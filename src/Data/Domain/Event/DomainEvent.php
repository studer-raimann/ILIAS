<?php
/* Copyright (c) 2019 Martin Studer <ms@studer-raimann.ch> Extended GPL, see docs/LICENSE - inspired by https://github.com/buttercup-php/protects */

namespace ILIAS\Data\Domain\Event;

use AggregateRevision;
use DateTime;
use ILIAS\Data\Domain\Entity\AggregateId;

/**
 * Something that happened in the past, that is of importance to the business.
 */
interface DomainEvent {

	/**
	 * The Aggregate this event belongs to.
	 *
	 * @return AggregateId
	 */
	public function getAggregateId(): AggregateId;


	/**
	 * @return AggregateRevision
	 */
	//public function getRevision(): AggregateRevision;


	/**
	 * @return string
	 */
	public function getEventName(): string;


	/**
	 * @return DateTime
	 */
	public function getOccuredOn();


	/**
	 * @return int
	 */
	public function getInitiatingUserId(): int;


	/**
	 * @return string
	 */
	public function getEventBody(): string;
}