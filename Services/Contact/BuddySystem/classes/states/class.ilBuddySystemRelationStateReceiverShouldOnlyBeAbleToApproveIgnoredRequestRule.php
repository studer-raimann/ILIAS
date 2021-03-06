<?php declare(strict_types=1);
/* Copyright (c) 1998-2015 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * Class ilBuddySystemRelationStateReceiverShouldOnlyBeAbleToApproveIgnoredRequestRule
 * @author Michael Jansen <mjansen@databay.de>
 */
class ilBuddySystemRelationStateReceiverShouldOnlyBeAbleToApproveIgnoredRequestRule extends ilBuddySystemRelationStateFilterRule
{
    public function matches() : bool
    {
        if (!$this->relation->isIgnored()) {
            return false;
        }

        if ($this->relation->isOwnedByActor()) {
            return false;
        }

        return true;
    }

    public function __invoke(ilBuddySystemRelationState $state) : bool
    {
        if ($state instanceof ilBuddySystemLinkedRelationState) {
            return true;
        }

        return false;
    }
}
