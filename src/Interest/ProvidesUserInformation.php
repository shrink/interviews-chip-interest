<?php

declare(strict_types=1);

namespace Shrink\Chip\Interest;

interface ProvidesUserInformation
{
    public function userById(UserId $id): User;
}
