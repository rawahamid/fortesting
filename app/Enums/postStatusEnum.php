<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class postStatusEnum extends Enum
{
    const Draft    =   'Draft';
    const Pending  =   'Pending';
    const Publish  =   'Published';
}
