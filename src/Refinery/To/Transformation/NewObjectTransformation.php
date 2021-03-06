<?php declare(strict_types=1);

/* Copyright (c) 1998-2019 ILIAS open source, Extended GPL, see docs/LICENSE */

namespace ILIAS\Refinery\To\Transformation;

use ILIAS\Refinery\DeriveApplyToFromTransform;
use ILIAS\Refinery\Transformation;

/**
 * @author  Niels Theen <ntheen@databay.de>
 */
class NewObjectTransformation implements Transformation
{
    use DeriveApplyToFromTransform;

    private string $className;

    public function __construct(string $className)
    {
        $this->className = $className;
    }

    /**
     * @inheritdoc
     * @throws \ReflectionException
     */
    public function transform($from)
    {
        $class = new \ReflectionClass($this->className);
        $instance = $class->newInstanceArgs($from);

        return $instance;
    }

    /**
     * @inheritdoc
     * @throws \ReflectionException
     */
    public function __invoke($from)
    {
        return $this->transform($from);
    }
}
