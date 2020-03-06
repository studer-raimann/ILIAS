<?php

namespace ILIAS\AssessmentQuestion\Questions\FileUpload;

use ILIAS\AssessmentQuestion\DomainModel\AbstractConfiguration;

/**
 * Class FileUploadScoringConfiguration
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class FileUploadScoringConfiguration extends AbstractConfiguration {
    /**
     * @var ?float
     */
    protected $points;
    
    /**
     * @var ?bool
     */
    protected $completed_by_submition;
    
    /**
     * @param int $points
     * @param bool $completed_by_submition
     * @return FileUploadScoringConfiguration
     */
    static function create(?float $points = null, ?bool $completed_by_submition = null) : FileUploadScoringConfiguration
    {
        $object = new FileUploadScoringConfiguration();
        $object->points = $points;
        $object->completed_by_submition = $completed_by_submition;
        return $object;
    }
    
    /**
     * @return int
     */
    public function getPoints() : ?float {
        return $this->points;
    }
    
    /**
     * @return boolean
     */
    public function isCompletedBySubmition() : ?bool {
        return $this->completed_by_submition;
    }
}