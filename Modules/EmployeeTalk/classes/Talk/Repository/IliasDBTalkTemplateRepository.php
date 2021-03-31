<?php
declare(strict_types=1);

namespace ILIAS\Modules\EmployeeTalk\Talk\Repository;

use ilObjTalkTemplate;
use ilTree;
use ilObjTalkTemplateAdministration;

final class IliasDBTalkTemplateRepository
{
    /**
     * @var ilTree $tree
     */
    private $tree;

    /**
     * IliasDBTalkTemplateRepository constructor.
     * @param ilTree $tree
     */
    public function __construct(ilTree $tree)
    {
        $this->tree = $tree;
    }

    /**
     * @return ilObjTalkTemplate[]
     */
    public function findAll(): array {
        $rawTemplates = $this->tree->getChildsByType(ilObjTalkTemplateAdministration::getRootRefId(), ilObjTalkTemplate::TYPE);
        $templates = array_map(function(array $template) {
            return new ilObjTalkTemplate($template['ref_id']);
        }, $rawTemplates);
        return $templates;
    }
}