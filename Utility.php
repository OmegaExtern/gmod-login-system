<?php
require_once('Constants.php');

/**
 * Class Utility
 */
class Utility
{
    const Long = '76561197960265728';

    /*public static $first;

    private static function Utility() {
        self::$first = bcadd(self::Long, '1');
    }*/

    /**
     * @param $value double|float|number Value to clamp.
     * @param $max double|float|number Maximum range.
     * @param $min double|float|number Minimum range.
     * @return mixed double|float|number Returns clamped value (min <= value <= max).
     */
    public static function clamp($value, $max, $min)
    {
        return min(max($value, $min), $max);
    }

    /**
     * @param $value bool Boolean to convert.
     * @return int Returns 1 if the given value is TRUE; otherwise 0.
     */
    public static function bool2int($value)
    {
        return $value ? 1 : 0;
    }

    /**
     * @param $value int Integer to convert.
     * @return bool Returns TRUE if the given value is greater than 0; otherwise FALSE.
     */
    public static function int2bool($value)
    {
        return self::clamp($value, 0, 1) === 1;
    }

    /**
     * @param $value string String to sanitize.
     * @return string Returns sanitized string of the given value.
     */
    public static function sanitizeInput($value)
    {
        return trim(str_replace('\n', '', filter_var(htmlspecialchars(stripslashes(strip_tags(trim($value))), ENT_QUOTES, 'UTF-8'), FILTER_SANITIZE_STRING)));
    }

    /**
     * @param $community_identifier string Value to validate.
     * @return bool Returns TRUE if the given community_identifier is valid; otherwise FALSE.
     */
    public static function isValidCommunityId($community_identifier)
    {
        return preg_match('/' . COMMUNITY_ID_REGEX . '/A', strval($community_identifier)) === 1;
    }

    /**
     * @param $name string Name of the player.
     * @return bool Returns TRUE if given name is valid; otherwise FALSE.
     */
    public static function isValidName($name)
    {
        return preg_match('/' . NAME_REGEX . '/A', strval($name)) === 1;
    }

    /**
     * @param $steam_identifier string Value to validate.
     * @return bool Returns TRUE if the given steam_identifier is valid; otherwise FALSE.
     */
    public static function isValidSteamId($steam_identifier)
    {
        return preg_match('/' . STEAM_ID_REGEX . '/A', strval($steam_identifier)) === 1;
    }

    /**
     * @param $community_identifier string Community ID to convert into Steam ID.
     * @return string Returns Steam ID representation of the given community_identifier.
     */
    public static function communityId2steamId($community_identifier)
    {
        if (!self::isValidCommunityId($community_identifier)) {
            throw new InvalidArgumentException('community_identifier is not valid.');
        }
        $community_identifier = bcsub(strval($community_identifier), self::Long);
        $id = bcmod(strval($community_identifier), '2');
        $community_identifier = bcsub(strval($community_identifier), strval($id));
        return 'STEAM_0:' . $id . ':' . bcdiv(strval($community_identifier), '2');
    }

    /**
     * @param $steam_identifier string Steam ID to convert into community ID.
     * @return string Returns community ID representation of the given Steam ID.
     */
    public static function steamId2communityId($steam_identifier)
    {
        if (!self::isValidSteamId($steam_identifier)) {
            throw new InvalidArgumentException('steam_identifier is not valid.');
        }
        return bcadd(bcadd(bcmul(substr($steam_identifier, 10), '2'), self::Long), substr($steam_identifier, 8, 1));
    }
}