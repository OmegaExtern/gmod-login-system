<?php
/**
 * BooleanEnum.php
 * @license The MIT License (MIT) < http://opensource.org/licenses/MIT >
 * @author OmegaExtern < https://github.com/OmegaExtern > < omegaextern@live.com >
 */

require_once('Enum.php');

/**
 * Interface IBoolean
 */
interface IBoolean
{
    const NO = 'NO';
    const YES = 'YES';
}

/**
 * Class BooleanEnum
 */
class BooleanEnum extends Enum implements IBoolean
{
    /**
     * @param string $boolean
     * @return bool
     */
    public static function boolean2bool($boolean = IBoolean::NO)
    {
        return strtoupper(strval($boolean)) === IBoolean::YES;
    }

    /**
     * @param string $boolean
     * @return string
     */
    public static function boolean2string($boolean = IBoolean::NO)
    {
        return strtoupper(strval($boolean)) === IBoolean::YES ? 'true' : 'false';
    }

    /**
     * @param bool $bool
     * @return string
     */
    public static function bool2boolean($bool = false)
    {
        return $bool ? IBoolean::YES : IBoolean::NO;
    }

    /**
     * @param bool $bool
     * @return string
     */
    public static function bool2string($bool = false)
    {
        return $bool ? 'true' : 'false';
    }

    /**
     * @param string $name
     * @return string
     */
    public static function getBoolean($name = IBoolean::NO)
    {
        $boolean = constant('IBoolean::' . strtoupper(strval($name)));
        if ($boolean === null) {
            return IBoolean::NO;
        }
        return strval($boolean);
    }
}

// echo BooleanEnum::boolean2bool('YES') . '<br>' . BooleanEnum::bool2boolean(true) . '<br>' . BooleanEnum::getBoolean('YES') . '<br>' . BooleanEnum::getBoolean('nope');
