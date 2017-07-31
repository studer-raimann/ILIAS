<?php

namespace ILIAS\UI\Component\Dropzone\File;

use ILIAS\UI\Component\Component;

/**
 * Interface Factory
 *
 * Describes a factory for file dropzones.
 *
 * @author  nmaerchy <nm@studer-raimann.ch>
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 *
 * @package ILIAS\UI\Component\Dropzone\File
 */
interface Factory {

	/**
	 * ---
	 * description:
	 *   purpose: >
	 *      The standard dropzone is used to drop files dragged from outside
	 *      the browser window to upload them as part of a form. The dropped
	 *      files are presented to the user and can be uploaded to the server.
	 *   composition: >
	 *      Standard dropzones consist of a visible area where files can
	 *      be dropped. They MUST contain a message explaining that it is possible to
	 *      drop files inside. The dropped files are presented to the user.
	 *   effect: >
	 *      A standard dropzone is highlighted when the user is dragging files
	 *      over the dropzone. After dropping, the dropped files are presented
	 *      to the user with some meta information of the files such the file name
	 *      and file size.
	 *   rivals:
	 *      Rival 1: >
	 *          A wrapper dropzone is used when some UI component should be enhanced
	 *          with dropzone functionality.
	 *
	 * rules:
	 *   usage:
	 *     1: Standard Dropzones MUST contain a message.
	 *     1: Standard Dropzones MUST only be used in forms.
	 *     2: >
	 *        The upload button MUST be disabled if there are no files
	 *        to be uploaded. Only true if the dropzone is NOT used in
	 *        a form containing other form elements.
	 *     3: >
	 *        Standard dropzones MAY be used in forms.
	 *   accessibility:
	 *     1: >
	 *        Standard dropzones MUST offer the possibility to select files
	 *        manually from the computer.
	 *
	 * ---
	 *
	 * @param string $url The url where the dropped files are being uploaded
	 * @return \ILIAS\UI\Component\Dropzone\File\Standard
	 */
	public function standard($url);


	/**
	 * ---
	 * description:
	 *   purpose: >
	 *      A wrapper dropzone is used to enhance another UI component with additional
	 *      dropzone functionality.  They can be introduced to offer an additional
	 *      approach to complete some file upload workflow more conveniently. Especially
	 *      in situation where space is scarce such as appointments in the calendar.
	 *   composition: >
	 *      A Wrapper File Dropzone contains one or multiple ILIAS UI components.
	 *      A Roundtrip Modal is used to present the dropped files in list as described
	 *      for the File Dropzone and to initialize the upload process.
	 *   effect: >
	 *      The Wrapper File Dropzone is not visible by default but instead shows its content
	 *      unmodified. When the user drags a file over the browser window, all wrapper
	 *      dropzones on the page where the file can be dropped are highlighted. Thus, a
	 *      user needs to have the knowledge that there are wrapper dropzones present. After
	 *      dropping the file, a Roundtrip Modal shows the list of already dropped files and
	 *      a Button to start the upload.
	 *   rivals:
	 *      Rival 1: >
	 *          A Standard Dropzone is used in a form and displays a message instead
	 *          of other ILIAS UI components.
	 *
	 * rules:
	 *   usage:
	 *     1: Wrapper File Dropzones MUST contain one or more ILIAS UI components.
	 *     2: Wrapper File Dropzones MUST only be used for Items in Item Listings.
	 *     2: Wrapper File Dropzones MUST NOT contain any other file dropzones.
	 *     3: Wrapper File Dropzones MUST NOT be used in modals.
	 *     4: >
	 *        The upload button in the modal MUST be disabled if there are no files
	 *        to be uploaded.
	 * ---
	 *
	 * @param string $url The url where the dropped files are being uploaded
	 * @param Component[]|Component $content Component(s) wrapped by the dropzone
	 * @return \ILIAS\UI\Component\Dropzone\File\Wrapper
	 */
	public function wrapper($url, $content);


}
