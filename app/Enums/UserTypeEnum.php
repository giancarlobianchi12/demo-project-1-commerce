<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class UserTypeEnum extends Enum
{
    const CLIENT = 'client';

    const ADMIN = 'admin';

    const DRIVER = 'driver';
}
