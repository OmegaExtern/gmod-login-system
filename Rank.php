<?php
require_once('Enum.php');

interface IRank
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

abstract class Rank extends Enum implements IRank
{
    /**
     * @param string $name Name of rank.
     * @return int|mixed Returns rank priority with the given name.
     */
    public static function getRankPriorityByName($name = 'UNKNOWN')
    {
        $rank = constant('IRank::' . strtoupper(strval($name)));
        if ($rank === null) {
            return IRank::UNKNOWN;
        }
        return $rank;
    }

    /**
     * @param int $priority Rank priority.
     * @return string Returns rank name with the given priority.
     */
    public static function getRankNameByPriority($priority = IRank::UNKNOWN)
    {
        foreach (self::getConstants() as $key => $value) {
            if ($value === $priority) {
                return strval($key);
            }
        }
        return 'UNKNOWN';
    }
}

// echo Rank::getRankNameByPriority(1) . '<br>'. Rank::getRankPriorityByName('MEMBER');
