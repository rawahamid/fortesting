<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class PostStatusEnum extends Enum
{
    const DRAFT    =   'Draft';
    const PENDING  =   'Pending';
    const PUBLISH  =   'Published';
}
