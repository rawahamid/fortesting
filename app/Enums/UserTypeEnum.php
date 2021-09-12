<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class UserTypeEnum extends Enum
{
    const ADMIN     =  'sys_admin';
    const AUTHOR    = 'Author';
    const GUEST     = 'Guest';
}
