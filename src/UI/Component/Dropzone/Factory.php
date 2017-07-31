<?php
/**
 * Interface Factory
 *
 * Describes a factory implementation for ILIAS UI File Dropzones.
 *
 * @author  nmaerchy <nm@studer-raimann.ch>
 *
 * @package ILIAS\UI\Component\Dropzone
 */

namespace ILIAS\UI\Component\Dropzone;

interface Factory {

	/**
	 * ---
	 * description:
	 *   purpose: >
	 *      File dropzones are used to drop files from outside the browser window.
	 *      The dropped files are presented to the user and can be uploaded to the server.
	 *      File dropzones offer additional convenience beside manually selecting files
	 *      over the file browser.
	 *   composition: >
	 *      File dropzones are areas to drop the files. They contain either a message
	 *      (standard file dropzone) or other ILIAS UI components (wrapper file dropzone).
	 *      When the user already dropped files into the zone, these are displayed in a list.
	 *      A Remove glyph is shown for every list item.  If the user is allowed to set filenames
	 *      and or descriptions, the list entry shows an Expand glyph. 
	 *   effect: >
	 *      A dropzone is highlighted when the user drags files over it. When the dropzone
	 *      already contains files, these are removed by using the Remove glyph. The Expand glyph
	 *      opens a form to enter filename and/or description in an accordion.
	 *
	 * rules:
	 *   accessibility:
	 *     1: >
	 *       There MUST be alternative ways in the system to upload the files due to
	 *       the limited accessibility of file dropzones.
	 * ---
	 *
	 * @return \ILIAS\UI\Component\Dropzone\File\Factory
	 **/
	public function file();
}
