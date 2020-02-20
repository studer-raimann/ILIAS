<?php

namespace ILIAS\AssessmentQuestion\Questions\FileUpload;


use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class FileUploadAnswer
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class FileUploadAnswer extends AbstractValueObject {
    /**
     * @var ?string[]
     */
    protected $files;
    
    public static function create(?array $files = []) {
        $object = new FileUploadAnswer();
        $object->files = $files;
        return $object;
    }
    
    public function getFiles() : ?array {
        return $this->files;
    }
}