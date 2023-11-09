<?php

namespace App\Utils;

class InvitationConstants {

    public const STATUS_PENDING = 'pending';
    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_DECLINED = 'declined';
    public const STATUS_CANCELLED = 'cancelled';

    public const ACCEPT_SUCCESS_MSG = 'Invitation accepted successfully.';
    public const ACCEPT_ERROR_MSG = 'The invitation cannot be accepted.';
    public const DECLINE_SUCCESS_MSG = 'Invitation declined successfully.';
    public const DECLINE_ERROR_MSG = 'The invitation cannot be declined.';
    public const CANCELL_SUCCESS_MSG = 'Invitation cancelled successfully.';
    public const CANCELL_ERROR_MSG = 'The invitation cannot be cancelled.';
}
