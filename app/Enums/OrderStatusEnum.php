<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class OrderStatusEnum extends Enum
{
    const SHIPPED = 'shipped';

    const DELIVERED = 'delivered';

    const NOT_DELIVERED = 'not_delivered';

    const CANCELLED = 'cancelled';

    const RETURNED_WITHOUT_PAYMENTS = 'returned_without_payments';

    const RETURNED_WITH_PAYMENTS = 'returned_with_payments';
}
