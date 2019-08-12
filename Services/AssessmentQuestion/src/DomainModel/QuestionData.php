<?php

namespace ILIAS\AssessmentQuestion\DomainModel;

use ILIAS\AssessmentQuestion\CQRS\Aggregate\AbstractValueObject;

/**
 * Class QuestionData
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class QuestionData extends AbstractValueObject {
	/**
	 * @var string
	 */
	protected $title;
	/**
	 * @var string
	 */
	protected $description;
	/**
	 * @var string
	 */
	protected $question_text;
	/**
	 * @var string
	 */
	protected $author;
	/**
	 * @var int
	 */
	protected $working_time;

	/**
	 * @param string      $title
	 * @param string      $text
	 * @param string      $author
	 * @param string|null $description
	 * @param int         $working_time
	 *
	 * @return QuestionData
	 */
	static function create(string $title, string $text, string $author, string $description = null, int $working_time = 0) {
		$object = new QuestionData();
		$object->title = $title;
		$object->description = $description;
		$object->question_text = $text;
		$object->author = $author;
		$object->working_time = $working_time;
		return $object;
	}

	/**
	 * @return string
	 */
	public function getTitle(): ?string {
		return $this->title;
	}

	/**
	 * @return string
	 */
	public function getDescription(): ?string {
		return $this->description;
	}

	/**
	 * @return string
	 */
	public function getQuestionText(): ?string {
		return $this->question_text;
	}

	/**
	 * @return string
	 */
	public function getAuthor(): ?string {
		return $this->author;
	}

	/**
	 * @return int
	 */
	public function getWorkingTime(): int {
		return $this->working_time;
	}

    /**
     * @param AbstractValueObject $other
     *
     * @return bool
     */
    public function equals(AbstractValueObject $other): bool
    {
        /** @var QuestionData $other */
        return $this->getAuthor() === $other->getAuthor() &&
               $this->getDescription() === $other->getDescription() &&
               $this->getQuestionText() === $other->getQuestionText() &&
               $this->getTitle() === $other->getTitle() &&
               $this->getWorkingTime() === $other->getWorkingTime();
    }
}