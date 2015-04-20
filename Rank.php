<?php

interface Rank
{
    const UNKNOWN = 0;
    const MEMBER = 1;
    const SUPER_MEMBER = 1 << 1;
    const MODERATOR = 1 << 2;
    const SUPER_MODERATOR = 1 << 3;
    const ADMINISTRATOR = 1 << 4;
    const SUPER_ADMINISTRATOR = 1 << 5;
    const OWNER = 1 << 6;
}
