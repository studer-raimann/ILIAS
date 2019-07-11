<?php

namespace ILIAS\Changelog\Infrastructure\Repository;


use ILIAS\Changelog\Events\Membership\AddedToCourse;
use ILIAS\Changelog\Events\Membership\MembershipRequestAccepted;
use ILIAS\Changelog\Events\Membership\MembershipRequestDenied;
use ILIAS\Changelog\Events\Membership\MembershipRequested;
use ILIAS\Changelog\Events\Membership\RemovedFromCourse;
use ILIAS\Changelog\Events\Membership\SubscribedToCourse;
use ILIAS\Changelog\Events\Membership\UnsubscribedFromCourse;
use ILIAS\Changelog\Interfaces\Repository;
use ILIAS\Changelog\Query\Requests\getLogsOfCourseRequest;
use ILIAS\Changelog\Query\Requests\getLogsOfUserRequest;
use ILIAS\Changelog\Query\Responses\getLogsOfCourseResponse;
use ILIAS\Changelog\Query\Responses\getLogsOfUserResponse;

/**
 * Class MembershipRepository
 * @package ILIAS\Changelog\Membership\Repository
 *
 * @author Theodor Truffer <tt@studer-raimann.ch>
 */
abstract class MembershipRepository implements Repository {

	/**
	 * @param MembershipRequested $membershipRequested
	 */
	abstract public function saveMembershipRequested(MembershipRequested $membershipRequested);

	/**
	 * @param MembershipRequestAccepted $membershipRequestAccepted
	 */
	abstract public function saveMembershipRequestAccepted(MembershipRequestAccepted $membershipRequestAccepted);

	/**
	 * @param MembershipRequestDenied $membershipRequestDenied
	 */
	abstract public function saveMembershipRequestDenied(MembershipRequestDenied $membershipRequestDenied);

	/**
	 * @param SubscribedToCourse $subscribedToCourse
	 */
	abstract public function saveSubscribedToCourse(SubscribedToCourse $subscribedToCourse);

	/**
	 * @param UnsubscribedFromCourse $unsubscribedFromCourse
	 */
	abstract public function saveUnsubscribedFromCourse(UnsubscribedFromCourse $unsubscribedFromCourse);

	/**
	 * @param RemovedFromCourse $removedFromCourse
	 * @return mixed
	 */
	abstract public function saveRemovedFromCourse(RemovedFromCourse $removedFromCourse);

	/**
	 * @param AddedToCourse $addedToCourse
	 * @return mixed
	 */
	abstract public function saveAddedToCourse(AddedToCourse $addedToCourse);

	/**
	 * @param getLogsOfUserRequest $getLogsOfUserRequest
	 * @return getLogsOfUserResponse
	 */
	abstract public function getLogsOfUser(getLogsOfUserRequest $getLogsOfUserRequest): getLogsOfUserResponse;

	/**
	 * @param getLogsOfCourseRequest $getLogsOfCourseRequest
	 * @return getLogsOfCourseResponse
	 */
	abstract public function getLogsOfCourse(getLogsOfCourseRequest $getLogsOfCourseRequest): getLogsOfCourseResponse;
}