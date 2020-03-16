<?php

namespace ILIAS\AssessmentQuestion\Infrastructure\Persistence;

use ILIAS\AssessmentQuestion\DomainModel\Answer\Answer;
use ActiveRecord;
use srag\CQRS\Aggregate\Guid;

/**
 * Class SimpleStoredAnswer
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class SimpleStoredAnswer extends ActiveRecord {
    const STORAGE_NAME = "asq_stored_answer";
    
    /**
     * @var int
     *
     * @con_is_primary true
     * @con_is_unique  true
     * @con_has_field  true
     * @con_fieldtype  integer
     * @con_length     8
     * @con_sequence   true
     */
    protected $id;
    /**
     * @var string
     *
     * @con_has_field  true
     * @con_fieldtype  text
     * @con_length     40
     * @con_index      true
     * @con_is_notnull true
     */
    protected $uuid;
    /**
     * @var int
     *
     * @con_has_field  true
     * @con_fieldtype  integer
     * @con_is_notnull true
     */
    protected $version;

    /**
     * @var string
     *
     * @con_has_field  true
     * @con_fieldtype  clob
     * @con_is_notnull true
     */
    protected $answer;
   

    public static function createNew(Answer $answer, ?string $uuid = null) {
        $object = new SimpleStoredAnswer();
        $object->uuid = $uuid ?? Guid::create();
        
        $history = SimpleStoredAnswer::where(['uuid' => $object->uuid])->get();
        
        if (count($history) > 0) {
            $object->version = array_reduce($history, function($max, SimpleStoredAnswer $item) {
                return max($max, $item->getVersion() + 1);
            }, 0);
        }
        else {
            $object->version = 1;
        }
        
        $object->answer = json_encode($answer);
        
        return $object;
    }
    
    /**
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }
    
    /**
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
    }
    
    /**
     * @return Answer
     */
    public function getAnswer() : Answer
    {
        return Answer::deserialize($this->answer);
    }
    
    /**
     * @return string
     */
    public static function returnDbTableName() {
        return self::STORAGE_NAME;
    }
}