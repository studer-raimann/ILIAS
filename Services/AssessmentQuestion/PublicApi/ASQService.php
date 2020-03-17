<?php
declare(strict_types=1);

namespace ILIAS\Services\AssessmentQuestion\PublicApi;

/**
 * Class ASQService
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 *
 * @package ILIAS\Services\AssessmentQuestion\PublicApi
 */
abstract class ASQService {
    /**
     * @var ?int
     */
    private $user_id;
    
    protected function getActiveUser() : int{
        global $DIC;
        
        return $this->user_id ?? $DIC->user()->getId();
    }
    
    public function setActiveUser(int $id) {
        $this->user_id = $id;
    }
}