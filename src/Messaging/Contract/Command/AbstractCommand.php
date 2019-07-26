<?php
/* Copyright (c) 2019 - Martin Studer <ms@studer-raimann.ch> - Extended GPL, see LICENSE */

namespace ILIAS\Messaging\Contract\Command;

use DateTime;

abstract class AbstractCommand implements Command {
	/**
	 * @var int
	 */
	protected $issuing_user_id;


	/**
	 * AbstractCommand constructor.
	 *
	 * @param int $issuing_user_id
	 */
	public function __construct(int $issuing_user_id) {
		$this->issuing_user_id = $issuing_user_id;
	}
}