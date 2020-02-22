<?php
declare(strict_types=1);

namespace ILIAS\Services\AssessmentQuestion\PublicApi\Authoring;

use ILIAS\AssessmentQuestion\DomainModel\QuestionDto;
use ILIAS\AssessmentQuestion\Infrastructure\Persistence\EventStore\QuestionEventStoreRepository;
class AuthoringQuestionList
{

    /**
     * @var int
     */
    protected $container_obj_id;
    /**
     * @var int
     */
    protected $actor_user_id;

    /**
     * ProcessingQuestionList constructor.
     *
     * @param int $container_obj_id
     * @param int $actor_user_id
     */
    public function __construct(int $container_obj_id, int $actor_user_id)
    {
        global $DIC;

        $this->container_obj_id = $container_obj_id;
        $this->actor_user_id = $actor_user_id;
        //The lng_key could be used in future as parameter in the constructor
        $lng_key = $DIC->language()->getDefaultLanguage();
    }

    public function getQuestionsOfContainerAsAssocArray() : array
    {
        $assoc_array = [];
        foreach($this->getQuestions() as $question_dto) {
            $question_array = $this->getArrayFromDto($question_dto);
            
            // TODO look for a less smelly way to set title if no title is set yet as it needs something for the link to be clickable
            if (!array_key_exists('data_title', $question_array)) {
                $question_array['data_title'] = '---';
            }
            
            $assoc_array[] = $question_array;
        }
        
        
        
        return $assoc_array;
    }

    /**
     *
     * @return QuestionDto[]
     */
    public function getQuestions(?bool $is_complete = null): array
    {
        global $DIC;
        
        $questions = [];
        $event_store = new QuestionEventStoreRepository();
        foreach ($event_store->allStoredQuestionIdsForContainerObjId($this->container_obj_id) as $aggregate_id) {
            $question = $DIC->assessment()->question()->getQuestionByQuestionId($aggregate_id);

            if (! is_null($is_complete)) {
                if ($question->isComplete() !== $is_complete) {
                    continue;
                }
            }

            $questions[] = $question;
        }

        return $questions;
    }
                    
    
    //TODO move and cleanup this method
    protected function getArrayFromDto($dto) {
        $name = get_class ($dto);
        $name = str_replace('\\', "\\\\", $name);
        $raw = (array)$dto;
        $attributes = array();
        foreach ($raw as $attr => $val) {
            if(is_object($val)) {
                $val_arr = $this->getArrayFromDto($val);
                $prefix = preg_replace('('.$name.'|\*|)', '', $attr);
                $prefixed_arr = [];
                foreach($val_arr as $key => $value) {
                    $attributes[preg_replace ( '/[^a-z0-9_ ]/i', '',$prefix."_".$key)] = $value;
                }
            } else {
                $val_arr = $val;


                $attributes[preg_replace ( '/[^a-z0-9 ]/i', '', preg_replace('('.$name.'|\*|)', '', $attr))] = $val_arr;
            }
        }
       return $attributes;
    }
}