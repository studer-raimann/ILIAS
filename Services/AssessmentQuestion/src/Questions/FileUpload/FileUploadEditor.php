<?php

namespace ILIAS\AssessmentQuestion\Questions\FileUpload;

use ILIAS\AssessmentQuestion\DomainModel\AbstractConfiguration;
use ILIAS\AssessmentQuestion\DomainModel\Question;
use ILIAS\AssessmentQuestion\DomainModel\QuestionDto;
use ILIAS\AssessmentQuestion\UserInterface\Web\Component\Editor\AbstractEditor;
use ILIAS\AssessmentQuestion\UserInterface\Web\Component\Editor\EmptyDisplayDefinition;
use ILIAS\FileUpload\Location;
use ILIAS\FileUpload\DTO\ProcessingStatus;
use ilNumberInputGUI;
use ilTemplate;
use ilTextInputGUI;
use srag\CQRS\Aggregate\Guid;
use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class FileUploadEditor
 *
 * @package ILIAS\AssessmentQuestion\Authoring\DomainModel\Question\Answer\Option;
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class FileUploadEditor extends AbstractEditor {
    
    const VAR_MAX_UPLOAD = 'fue_max_upload';
    const VAR_ALLOWED_EXTENSIONS = 'fue_extensions';
    const VAR_CURRENT_ANSWER = 'fue_current_answer';
    
    const UPLOADPATH = 'asq/answers/';
    
    
    /**
     * @var FileUploadEditorConfiguration
     */
    private $configuration;
    
    public function __construct(QuestionDto $question) {
        $this->files = [];
        $this->configuration = $question->getPlayConfiguration()->getEditorConfiguration();
        
        parent::__construct($question);
    }
    
    public static function generateFields(?AbstractConfiguration $config): ?array {
        /** @var FileUploadEditorConfiguration $config */
        global $DIC;
        
        $fields = [];
        
        $max_upload = new ilNumberInputGUI($DIC->language()->txt('asq_label_max_upload'), self::VAR_MAX_UPLOAD);
        $max_upload->setInfo($DIC->language()->txt('asq_description_max_upload'));
        $fields[self::VAR_MAX_UPLOAD] = $max_upload;
        
        $allowed_extensions = new ilTextInputGUI($DIC->language()->txt('asq_label_allowed_extensions'), 
                                                 self::VAR_ALLOWED_EXTENSIONS);
        $allowed_extensions->setInfo($DIC->language()->txt('asq_description_allowed_extensions'));
        $fields[self::VAR_ALLOWED_EXTENSIONS] = $allowed_extensions;
        
        if ($config !== null) {
            $max_upload->setValue($config->getMaximumSize());
            $allowed_extensions->setValue($config->getAllowedExtensions());
        }
        
        return $fields;
    }
    
    public function readAnswer(): AbstractValueObject
    {
        global $DIC;
        
        $this->files = json_decode(html_entity_decode($_POST[$this->getPostVar() . self::VAR_CURRENT_ANSWER]), true);
        
        if ($DIC->upload()->hasUploads() && !$DIC->upload()->hasBeenProcessed()) {
            $this->UploadNewFile();
        }
        
        $this->deleteOldFiles();
        
        return FileUploadAnswer::create($this->files);
    }
    
    private function UploadNewFile() {
        global $DIC;
        
        $DIC->upload()->process();
        
        foreach ($DIC->upload()->getResults() as $result)
        {
            $folder = self::UPLOADPATH . $this->question->getId() . '/';
            $pathinfo = pathinfo($result->getName());
            
            $filename = Guid::create() . '.' . $pathinfo['extension'];
            
            if ($result && $result->getStatus()->getCode() === ProcessingStatus::OK && 
                $this->checkAllowedExtension($pathinfo['extension'])) {
                $DIC->upload()->moveOneFileTo(
                    $result,
                    $folder,
                    Location::WEB,
                    $filename);
                
                $this->files[$pathinfo['basename']] = ILIAS_HTTP_PATH . '/' .
                                            ILIAS_WEB_DIR . '/' .
                                            CLIENT_ID .  '/' .
                                            $folder .
                                            $filename;
            }
        }
    }

    private function deleteOldFiles() {
        if(!empty($this->files)) {
            $answers = $this->files;
            
            foreach (array_keys($answers) as $key) {
                if (array_key_exists($this->getFileKey($key), $_POST)) {
                    unset($this->files[$key]);
                }
            }
        }
    }
    
    /**
     * @param string $extension
     * @return bool
     */
    private function checkAllowedExtension(string $extension) :bool {
        return empty($this->configuration->getAllowedExtensions()) ||
               in_array($extension, explode(',', $this->configuration->getAllowedExtensions()));
    }
    
    public static function readConfig() : FileUploadEditorConfiguration
    {
        $max_upload = intval($_POST[self::VAR_MAX_UPLOAD]);
        
        if ($max_upload === 0) {
            $max_upload = null;
        }
        
        return FileUploadEditorConfiguration::create($max_upload, 
                                                     str_replace(' ', '', $_POST[self::VAR_ALLOWED_EXTENSIONS]));
    }

    public function generateHtml(): string
    {
        global $DIC;
        
        $tpl = new ilTemplate("tpl.FileUploadEditor.html", true, true, "Services/AssessmentQuestion");
        $tpl->setVariable('TXT_UPLOAD_FILE', $DIC->language()->txt('asq_header_upload_file'));
        $tpl->setVariable('TXT_MAX_SIZE', 
                          sprintf($DIC->language()->txt('asq_text_max_size'), 
                                  $this->configuration->getMaximumSize() ?? ini_get('upload_max_filesize')));
        $tpl->setVariable('POST_VAR', $this->getPostVar());
        $tpl->setVariable('CURRENT_ANSWER_NAME', $this->getPostVar() . self::VAR_CURRENT_ANSWER);
        $tpl->setVariable('CURRENT_ANSWER_VALUE', htmlspecialchars(json_encode(is_null($this->answer) ? null : $this->answer->getFiles())));
        
        if (!empty($this->configuration->getAllowedExtensions())) {
            $tpl->setCurrentBlock('allowed_extensions');
            $tpl->setVariable('TXT_ALLOWED_EXTENSIONS', 
                              sprintf($DIC->language()->txt('asq_text_allowed_extensions'), 
                                      $this->configuration->getAllowedExtensions()));
            $tpl->parseCurrentBlock();
            
        }
        
        if (!is_null($this->answer) && count($this->answer->getFiles()) > 0) {
            $tpl->setCurrentBlock('files');

            foreach ($this->answer->getFiles() as $key => $value) {
                $tpl->setCurrentBlock('file');
                $tpl->setVariable('FILE_ID', $this->getFileKey($key));
                $tpl->setVariable('FILE_NAME', $key);
                $tpl->setVariable('FILE_PATH', $value);
                $tpl->parseCurrentBlock();
            }
            
            $tpl->setVariable('HEADER_DELETE', $DIC->language()->txt('delete'));
            $tpl->setVariable('HEADER_FILENAME', $DIC->language()->txt('filename'));
            $tpl->parseCurrentBlock();
        }
        
        return $tpl->get();
    }
    
    private function getPostVar() : string {
        return $this->question->getId();
    }
    
    private function getFileKey(string $filename) {
        return $this->getPostVar() . str_replace('.', '', $filename);
    }

    public static function getDisplayDefinitionClass() : string {
        return EmptyDisplayDefinition::class;
    }
    
    public static function isComplete(Question $question): bool
    {
        /** @var FileUploadEditorConfiguration $config */
        $config = $question->getPlayConfiguration()->getEditorConfiguration();
        
        return true;
    }
}