<?php

namespace ILIAS\AssessmentQuestion\Authoring\DomainModel\Question;

class QuestionDto {

	/**
	 * @var string
	 */
	private $id;
	/**
	 * @var string
	 */
	private $revision_id;
	/**
	 * @var string
	 */
	private $revision_name = "";
	/**
	 * @var int
	 */
	private $creator_id;
	/**
	 * @var QuestionData
	 */
	private $data;

	public static function CreateFromQuestion(Question $question) : QuestionDto {
		$dto = new QuestionDto();
		$dto->id = $question->getAggregateId()->getId();

		if ($question->getRevisionId() !== null) {
			$dto->revision_id = $question->getRevisionId()->getKey();
			$dto->revision_name = $question->getRevisionName();
		}

		$dto->creator_id = $question->getCreatorId();
		$dto->data = $question->getData();
		return $dto;
	}

	/**
	 * @return string
	 */
	public function getId(): string {
		return $this->id;
	}


	/**
	 * @return string
	 */
	public function getRevisionId(): string {
		return $this->revision_id;
	}


	/**
	 * @return string
	 */
	public function getRevisionName(): string {
		return $this->revision_name;
	}


	/**
	 * @return int
	 */
	public function getCreatorId(): int {
		return $this->creator_id;
	}

	/**
	 * @return QuestionData
	 */
	public function getData(): QuestionData {
		return $this->data;
	}


	/**
	 * @param QuestionData $data
	 */
	public function setData(QuestionData $data): void {
		$this->data = $data;
	}
}