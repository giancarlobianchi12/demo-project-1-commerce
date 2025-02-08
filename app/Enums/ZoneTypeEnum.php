<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ZoneTypeEnum extends Enum
{
    const SHORT = 'short';

    const MEDIUM = 'medium';

    const LONG = 'long';

    const NOT_RECOGNIZED = 'not_recognized';
}
