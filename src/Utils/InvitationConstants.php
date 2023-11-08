<?php

namespace App\Utils;

class InvitationConstants {

    public const STATUS_PENDING = 'pending';
    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_CANCELLED = 'cancelled';

    public const ACCEPT_SUCCESS_MSG = 'Invitation accepted successfully.';
    public const ACCEPT_ERROR_MSG = 'The invitation cannot be accepted.';
}
