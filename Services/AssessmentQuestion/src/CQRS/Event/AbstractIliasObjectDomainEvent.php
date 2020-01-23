<?php
/* Copyright (c) 2019 Extended GPL, see docs/LICENSE */

namespace ILIAS\AssessmentQuestion\CQRS\Event;

use ilDateTime;
use ILIAS\AssessmentQuestion\CQRS\Aggregate\DomainObjectId;

/**
 * Class AbstractDomainEvent
 *
 * @package ILIAS\Data\Domain\Event
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
abstract class AbstractIliasObjectDomainEvent extends AbstractDomainEvent
{
    /**
     * @var int;
     */
    protected $container_obj_id;
    /**
     * @var int
     */
    protected $object_id;
    
    /**
     * AbstractQuestionEvent constructor.
     *
     * @param DomainObjectId $aggregate_id
     * @param int            $container_obj_id
     * @param int            $initiating_user_id
     * @param int            $object_id
     *
     * @throws \ilDateTimeException
     */
    public function __construct(DomainObjectId $aggregate_id, int $container_obj_id, int $initiating_user_id, int $object_id)
    {
        parent::__construct($aggregate_id, $initiating_user_id);
        $this->container_obj_id = $container_obj_id;
        $this->object_id = $object_id;
    }

    /**
     * @return int
     */
    public function getContainerObjId() : int
    {
        return $this->container_obj_id;
    }

    /**
     * @return int
     */
    public function getObjectId(): int
    {
        return $this->object_id;
    }
}