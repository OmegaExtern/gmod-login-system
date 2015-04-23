<?php
/**
 * Enum.php
 * @license The MIT License (MIT) < http://opensource.org/licenses/MIT >
 * @author OmegaExtern < https://github.com/OmegaExtern > < omegaextern@live.com >
 */

/**
 * Class Enum
 */
abstract class Enum
{
    private static $constCacheArray = NULL;

    protected static function getConstants()
    {
        if (self::$constCacheArray == NULL) {
            self::$constCacheArray = [];
        }
        $calledClass = get_called_class();
        if (!array_key_exists($calledClass, self::$constCacheArray)) {
            $reflect = new ReflectionClass($calledClass);
            self::$constCacheArray[$calledClass] = $reflect->getConstants();
        }
        return self::$constCacheArray[$calledClass];
    }

    protected static function isValidName($name, $strict = true)
    {
        $constants = self::getConstants();
        if ($strict) {
            return array_key_exists($name, $constants);
        }
        $keys = array_map('strtoupper', array_keys($constants));
        return in_array(strtolower($name), $keys);
    }

    protected static function isValidValue($value)
    {
        $values = array_values(self::getConstants());
        return in_array($value, $values, $strict = true);
    }
}
