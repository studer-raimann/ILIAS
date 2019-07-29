<?php

namespace ILIAS\Messaging\Example\ExampleCourse\Domainmodel\Command;

use ILIAS\Messaging\Contract\Command\Command;

class addCourseMemberToCourseCommand implements Command {

	//TODO - should be AggregateId

	/**
	 * @var int
	 */
	public $course_id;
	/**
	 * @var int
	 */
	public $user_id;


	public function __construct(int $course_id, int $user_id) {
		$this->course_id = $course_id;
		$this->user_id = $user_id;
	}
}