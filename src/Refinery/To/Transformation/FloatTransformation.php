<?php declare(strict_types=1);

/* Copyright (c) 1998-2019 ILIAS open source, Extended GPL, see docs/LICENSE */

namespace ILIAS\Refinery\To\Transformation;

use ILIAS\Refinery\DeriveApplyToFromTransform;
use ILIAS\Refinery\Transformation;
use ILIAS\Refinery\ConstraintViolationException;
use ILIAS\Refinery\DeriveInvokeFromTransform;

/**
 * @author  Niels Theen <ntheen@databay.de>
 */
class FloatTransformation implements Transformation
{
    use DeriveApplyToFromTransform;
    use DeriveInvokeFromTransform;

    /**
     * @inheritdoc
     */
    public function transform($from)
    {
        if (false === is_float($from)) {
            throw new ConstraintViolationException(
                'The value MUST be of type float',
                'not_float'
            );
        }
        return (float) $from;
    }
}
