<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class userTypeEnum extends Enum
{
    const Admin     =  'sys_admin';
    const Author    = 'Author';
    const Guest     = 'Guest';
}
