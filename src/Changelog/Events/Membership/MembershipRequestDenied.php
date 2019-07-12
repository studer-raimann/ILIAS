<?php

namespace ILIAS\Changelog\Events\Membership;


use ILIAS\Changelog\Exception\CourseNotFoundException;
use ILIAS\Changelog\Exception\UserNotFoundException;
use ilObjCourse;
use ilObjUser;

/**
 * Class MembershipRequestDenied
 * @package ILIAS\Changelog\Events\Membership
 *
 * @author Theodor Truffer <tt@studer-raimann.ch>
 */
class MembershipRequestDenied extends MembershipEvent {

	const TYPE_ID = 3;


	/**
	 * @var int
	 */
	protected $crs_obj_id;
	/**
	 * @var int
	 */
	protected $requesting_user_id;
	/**
	 * @var int
	 */
	protected $denying_user_id;

	/**
	 * MembershipRequested constructor.
	 * @param int $crs_obj_id
	 * @param int $requesting_user_id
	 * @param int $denying_user_id
	 * @throws CourseNotFoundException
	 * @throws UserNotFoundException
	 */
	public function __construct(int $crs_obj_id, int $requesting_user_id, int $denying_user_id) {
		if (!ilObjCourse::_exists($crs_obj_id)) {
			throw new CourseNotFoundException("couldn't find course with obj_id " . $crs_obj_id);
		}
		if (!ilObjUser::_exists($requesting_user_id)) {
			throw new UserNotFoundException("couldn't find user with id " . $requesting_user_id);
		}
		if (!ilObjUser::_exists($denying_user_id)) {
			throw new UserNotFoundException("couldn't find user with id " . $denying_user_id);
		}
		$this->crs_obj_id = $crs_obj_id;
		$this->requesting_user_id = $requesting_user_id;
		$this->denying_user_id = $denying_user_id;
	}


	public function getTypeId(): int {
		return self::TYPE_ID;
	}

	/**
	 * @return int
	 */
	public function getCrsObjId(): int {
		return $this->crs_obj_id;
	}

	/**
	 * @return int
	 */
	public function getRequestingUserId(): int {
		return $this->requesting_user_id;
	}

	/**
	 * @return int
	 */
	public function getDenyingUserId(): int {
		return $this->denying_user_id;
	}

}