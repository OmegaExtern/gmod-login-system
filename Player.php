<?php
/**
 * Player.php
 * @license The MIT License (MIT) < http://opensource.org/licenses/MIT >
 * @author OmegaExtern < https://github.com/OmegaExtern > < omegaextern@live.com >
 */

require_once('Constants.php');
require_once('BooleanEnum.php');
require_once('Rank.php');
require_once('Utility.php');

/**
 * Class _Player
 */
class _Player
{
    /**
     * @var string Determines if the player is currently banned.
     */
    public $banned;
    /**
     * @var string DateTime when ban have occurred.
     */
    public $banned_date_time;
    /**
     * @var string DateTime when ban will lift.
     */
    public $banned_expire_date_time;
    /**
     * @var string Reason of ban.
     */
    public $banned_reason;
    /**
     * @var string Community identifier of the player.
     */
    public $community_identifier;
    /**
     * @var string DateTime of the most recent query.
     */
    public $date_time;
    /**
     * @var string Current player's total experience.
     */
    public $experience;
    /**
     * @var string Primary and unique identifier of the player.
     */
    public $identifier;
    /**
     * @var string DateTime record when the player has joined.
     */
    public $joined_date_time;
    /**
     * @var string Original player name record.
     */
    public $joined_name;
    /**
     * @var int Current player's level.
     */
    public $level;
    /**
     * @var string Current player's name.
     */
    public $name;
    /**
     * @var string Previous player's name.
     */
    public $old_name;
    /**
     * @var string Determines if the player is currently online.
     */
    public $online;
    /**
     * @var string Amount of points owned by the player.
     */
    public $points;
    /**
     * @var Rank Current player's rank/group.
     */
    public $rank;
    /**
     * @var string Steam identifier of the player.
     */
    public $steam_identifier;
    /**
     * @var int Warning percentage of the player.
     */
    public $warning_percentage;
}

/**
 * Class Player
 */
class Player extends _Player
{
    /**
     * Player constructor.
     */
    public function __construct()
    {
    }

    /**
     * Determines whether community ID already exists or not.
     * @param $db PDO Valid PDO of the database connection.
     * @param $community_identifier string Community ID of the player to check.
     * @return bool Returns TRUE if the given community ID exists; otherwise FALSE.
     * @throws Exception If $community_identifier is not valid.
     */
    public static function communityIdExists(PDO $db, $community_identifier)
    {
        if (!Utility::isValidCommunityId($community_identifier)) {
            throw new Exception('community_identifier is not valid.');
        }
        $stmt = $db->prepare('SELECT 1 FROM players WHERE community_identifier=? LIMIT 1');
        $stmt->bindParam(1, $community_identifier, PDO::PARAM_STR, MAX_COMMUNITY_ID_LENGTH);
        $success = $stmt->execute();
        if (!$success) {
            throw new PDOException('Failed to execute SQL query.');
        }
        return $stmt->rowCount() > 0;
    }

    /**
     * Determines whether name already exists or not.
     * @param $db PDO Valid PDO of the database connection.
     * @param $name string Name of the player to check.
     * @return bool Returns TRUE if the given name exists; otherwise FALSE.
     * @throws Exception If $name is not valid.
     */
    public static function nameExists(PDO $db, $name)
    {
        if (!Utility::isValidName($name)) {
            throw new Exception('name is not valid.');
        }
        $stmt = $db->prepare('SELECT 1 FROM players WHERE name=? LIMIT 1');
        $stmt->bindParam(1, $name, PDO::PARAM_STR, MAX_NAME_LENGTH);
        $success = $stmt->execute();
        if (!$success) {
            throw new PDOException('Failed to execute SQL query.');
        }
        return $stmt->rowCount() > 0;
    }

    /**
     * Determines whether Steam ID already exists or not.
     * @param $db PDO Valid PDO of the database connection.
     * @param $steam_identifier string Steam ID of the player to check.
     * @return bool Returns TRUE if the given Steam ID exists; otherwise FALSE.
     * @throws Exception If $steam_identifier is not valid.
     */
    public static function steamIdExists(PDO $db, $steam_identifier)
    {
        if (!Utility::isValidSteamId($steam_identifier)) {
            throw new Exception('steam_identifier is not valid.');
        }
        $stmt = $db->prepare('SELECT 1 FROM players WHERE steam_identifier=? LIMIT 1');
        $stmt->bindParam(1, $steam_identifier, PDO::PARAM_STR, MAX_STEAM_ID_LENGTH);
        $success = $stmt->execute();
        if (!$success) {
            throw new PDOException('Failed to execute SQL query.');
        }
        return $stmt->rowCount() > 0;
    }

    /**
     * @param $db PDO Valid PDO of the database connection.
     * @param $community_identifier string Community ID of the player.
     * @return null|Player Returns a new Player object on success; NULL on failure.
     * @throws Exception If $community_identifier is not valid.
     */
    public static function getPlayerByCommunityId(PDO $db, $community_identifier)
    {
        if (!Utility::isValidCommunityId($community_identifier)) {
            throw new Exception('community_identifier is not valid.');
        }
        $stmt = $db->prepare('SELECT * FROM players WHERE community_identifier=? LIMIT 1');
        $stmt->bindParam(1, $community_identifier, PDO::PARAM_STR, MAX_COMMUNITY_ID_LENGTH);
        $success = $stmt->execute();
        if (!$success) {
            throw new PDOException('Failed to execute SQL query.');
        }
        if ($stmt->rowCount() < 1) {
            return null;
        }
        $new = new self();
        $player = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, '_Player')[0];
        $new->setPlayer($player);
        // self::printPlayer($player);
        return $new;
    }

    /**
     * @param $db PDO Valid PDO of the database connection.
     * @param $name string Name of the player.
     * @return null|Player Returns a new Player object on success; NULL on failure.
     * @throws Exception If $name is not valid.
     */
    public static function getPlayerByName(PDO $db, $name)
    {
        if (!Utility::isValidName($name)) {
            throw new Exception('name is not valid.');
        }
        $stmt = $db->prepare('SELECT * FROM players WHERE name=? LIMIT 1');
        $stmt->bindParam(1, $name, PDO::PARAM_STR, MAX_NAME_LENGTH);
        $success = $stmt->execute();
        if (!$success) {
            throw new PDOException('Failed to execute SQL query.');
        }
        if ($stmt->rowCount() < 1) {
            return null;
        }
        $new = new self();
        $player = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, '_Player')[0];
        $new->setPlayer($player);
        // self::printPlayer($player);
        return $new;
    }

    /**
     * @param $db PDO Valid PDO of the database connection.
     * @param $steam_identifier string Steam ID of the player.
     * @return null|Player Returns a new Player object on success; NULL on failure.
     * @throws Exception If $steam_identifier is not valid.
     */
    public static function getPlayerBySteamId(PDO $db, $steam_identifier)
    {
        if (!Utility::isValidSteamId($steam_identifier)) {
            throw new Exception('steam_identifier is not valid.');
        }
        $stmt = $db->prepare('SELECT * FROM players WHERE steam_identifier=? LIMIT 1');
        $stmt->bindParam(1, $steam_identifier, PDO::PARAM_STR, MAX_STEAM_ID_LENGTH);
        $success = $stmt->execute();
        if (!$success) {
            throw new PDOException('Failed to execute SQL query.');
        }
        if ($stmt->rowCount() < 1) {
            return null;
        }
        $new = new self();
        $player = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, '_Player')[0];
        $new->setPlayer($player);
        // self::printPlayer($player);
        return $new;
    }

    /**
     * Echos _Player object as a Lua table.
     * @param $player _Player _Player object to print.
     */
    public static function printPlayer($player)
    {
        echo 'return {
    ["banned"] = ' . BooleanEnum::boolean2string($player->banned) . ',
    ["banned_date_time"] = "' . $player->banned_date_time . '",
    ["banned_expire_date_time"] = "' . $player->banned_expire_date_time . '",
    ["banned_reason"] = "' . $player->banned_reason . '",
    ["community_identifier"] = "' . $player->community_identifier . '",
    ["date_time"] = "' . $player->date_time . '",
    ["experience"] = ' . $player->experience . ',
    ["identifier"] = ' . $player->identifier . ',
    ["joined_date_time"] = "' . $player->joined_date_time . '",
    ["joined_name"] = "' . $player->joined_name . '",
    ["level"] = ' . $player->level . ',
    ["name"] = "' . $player->name . '",
    ["old_name"] = "' . $player->old_name . '",
    ["online"] = ' . BooleanEnum::boolean2string($player->online) . ',
    ["points"] = ' . $player->points . ',
    ["rank"] = "' . Rank::getRankNameByPriority(Rank::getRankPriorityByName($player->rank)) . '",
    ["steam_identifier"] = "' . $player->steam_identifier . '",
    ["warning_percentage"] = ' . $player->warning_percentage . '
}<br>';
    }

    /**
     * Attempts to login/logout.
     * @param $db PDO Valid PDO of the database connection.
     * @param bool $in TRUE = login; FALSE = logout.
     */
    public function connect(PDO $db, $in = true)
    {
        if (!self::communityIdExists($db, $this->community_identifier)) {
            exit('community_identifier does not exist.');
        }
        if (!self::steamIdExists($db, $this->steam_identifier)) {
            exit('steam_identifier does not exist.');
        }
        $stmt = $db->prepare('SELECT * FROM players WHERE community_identifier=? AND steam_identifier=? LIMIT 1');
        $stmt->bindParam(1, $this->community_identifier, PDO::PARAM_STR, MAX_COMMUNITY_ID_LENGTH);
        $stmt->bindParam(2, $this->steam_identifier, PDO::PARAM_STR, MAX_STEAM_ID_LENGTH);
        $success = $stmt->execute();
        if (!$success) {
            throw new PDOException('Failed to execute SQL query.');
        }
        if ($stmt->rowCount() < 1) {
            exit('player was not found.');
        }
        $player = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, '_Player')[0];
        if (BooleanEnum::boolean2bool($player->banned)) {
            $player = $this->updatePlayer($db);
            self::printPlayer($player);
            unset($player);
            exit('player is banned.');
        }
        if (($in && BooleanEnum::boolean2bool($player->online)) || (!$in && !BooleanEnum::boolean2bool($player->online))) {
            $player = $this->updatePlayer($db);
            self::printPlayer($player);
            unset($player);
            exit('player is already ' . ($in ? 'online' : 'offline') . '.');
        }
        $stmt = $db->prepare('UPDATE players SET online=? WHERE identifier=?');
        $stmt->bindValue(1, $in ? IBoolean::YES : IBoolean::NO);
        $stmt->bindParam(2, $player->identifier, PDO::PARAM_STR, MAX_IDENTIFIER_LENGTH);
        $success = $stmt->execute();
        if (!$success) {
            throw new PDOException('Failed to execute SQL query.');
        }
        $player = $this->updatePlayer($db);
        self::printPlayer($player);
        unset($player);
        echo ($in ? 'login' : 'logout') . ' successful.';
    }

    /**
     * Echos $this/Player object as a Lua table.
     */
    public function printThis()
    {
        echo 'return {
    ["banned"] = ' . BooleanEnum::boolean2string($this->banned) . ',
    ["banned_date_time"] = "' . $this->banned_date_time . '",
    ["banned_expire_date_time"] = "' . $this->banned_expire_date_time . '",
    ["banned_reason"] = "' . $this->banned_reason . '",
    ["community_identifier"] = "' . $this->community_identifier . '",
    ["date_time"] = "' . $this->date_time . '",
    ["experience"] = ' . $this->experience . ',
    ["identifier"] = ' . $this->identifier . ',
    ["joined_date_time"] = "' . $this->joined_date_time . '",
    ["joined_name"] = "' . $this->joined_name . '",
    ["level"] = ' . $this->level . ',
    ["name"] = "' . $this->name . '",
    ["old_name"] = "' . $this->old_name . '",
    ["online"] = ' . BooleanEnum::boolean2string($this->online) . ',
    ["points"] = ' . $this->points . ',
    ["rank"] = "' . Rank::getRankNameByPriority(Rank::getRankPriorityByName($this->rank)) . '",
    ["steam_identifier"] = "' . $this->steam_identifier . '",
    ["warning_percentage"] = ' . $this->warning_percentage . '
}<br>';
    }

    /**
     * Attempts to register.
     * @param $db PDO Valid PDO of the database connection.
     * @throws Exception If $this->community_identifier or $this->name or either $this->steam_identifier is not valid.
     */
    public function register(PDO $db)
    {
        if (!Utility::isValidCommunityId($this->community_identifier)) {
            throw new Exception('community_identifier is not valid.');
        }
        if (!Utility::isValidName($this->name)) {
            throw new Exception('name is not valid.');
        }
        if (!Utility::isValidSteamId($this->steam_identifier)) {
            throw new Exception('steam_identifier is not valid.');
        }
        if (self::communityIdExists($db, $this->community_identifier)) {
            exit('community_identifier already exists.');
        }
        if (self::nameExists($db, $this->name)) {
            exit('name already exists.');
        }
        if (self::steamIdExists($db, $this->steam_identifier)) {
            exit('steam_identifier already exists.');
        }
        $stmt = $db->prepare('INSERT INTO players (community_identifier, joined_name, name, old_name, steam_identifier) VALUES (?, ?, ?, ?, ?)');
        $stmt->bindParam(1, $this->community_identifier, PDO::PARAM_STR, MAX_COMMUNITY_ID_LENGTH);
        $stmt->bindParam(2, $this->name, PDO::PARAM_STR, MAX_NAME_LENGTH);
        $stmt->bindParam(3, $this->name, PDO::PARAM_STR, MAX_NAME_LENGTH);
        $stmt->bindParam(4, $this->name, PDO::PARAM_STR, MAX_NAME_LENGTH);
        $stmt->bindParam(5, $this->steam_identifier, PDO::PARAM_STR, MAX_STEAM_ID_LENGTH);
        $success = $stmt->execute();
        if (!$success) {
            throw new PDOException('Failed to execute SQL query.');
        }
        $player = $this->updatePlayer($db);
        self::printPlayer($player);
        unset($player);
        echo 'registration successful.';
    }

    /**
     * Assigns properties of _Player object to $this/Player object.
     * @param $player _Player _Player object.
     */
    public function setPlayer($player)
    {
        $this->banned = $player->banned;
        $this->banned_date_time = $player->banned_date_time;
        $this->banned_expire_date_time = $player->banned_expire_date_time;
        $this->banned_reason = $player->banned_reason;
        $this->community_identifier = $player->community_identifier;
        $this->date_time = $player->date_time;
        $this->experience = $player->experience;
        $this->identifier = $player->identifier;
        $this->joined_date_time = $player->joined_date_time;
        $this->joined_name = $player->joined_name;
        $this->level = $player->level;
        $this->name = $player->name;
        $this->old_name = $player->old_name;
        $this->online = $player->online;
        $this->points = $player->points;
        $this->rank = $player->rank;
        $this->steam_identifier = $player->steam_identifier;
        $this->warning_percentage = $player->warning_percentage;
    }

    /**
     * Attempts to update $this/Player with new values set from within an $array.
     * @param $db PDO Valid PDO of the database connection.
     * @param $array array Key-value pair to update (ex. array(':name' => 'NewName')).
     */
    public function update(PDO $db, $array)
    {
        if (!self::communityIdExists($db, $this->community_identifier)) {
            exit('community_identifier does not exist.');
        }
        if (!self::nameExists($db, $this->name)) {
            exit('name does not exist.');
        }
        if (!self::steamIdExists($db, $this->steam_identifier)) {
            exit('steam_identifier does not exist.');
        }
        $stmt = $db->prepare('SELECT * FROM players WHERE community_identifier=? AND steam_identifier=? LIMIT 1');
        $stmt->bindParam(1, $this->community_identifier, PDO::PARAM_STR, MAX_COMMUNITY_ID_LENGTH);
        $stmt->bindParam(2, $this->steam_identifier, PDO::PARAM_STR, MAX_STEAM_ID_LENGTH);
        $success = $stmt->execute();
        if (!$success) {
            throw new PDOException('Failed to execute SQL query.');
        }
        $array_count = count($array);
        if ($array_count < 1) {
            throw new InvalidArgumentException('array does not have any valid key-value pair(s).');
        }
        $player = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, '_Player')[0];
        $statement = 'UPDATE players SET';
        foreach ($array as $key => $value) {
            $statement = $statement . ' ' . substr($key, 1) . '=' . $key;
        }
        $statement = $statement . ' WHERE identifier=:identifier';
        $array = array_merge($array, array(':identifier' => $player->identifier));
        $stmt = $db->prepare($statement);
        $success = $stmt->execute($array);
        if (!$success) {
            throw new PDOException('Failed to execute SQL query.');
        }
        unset($array);
        $player = $this->updatePlayer($db);
        self::printPlayer($player);
        unset($player);
    }

    /**
     * Synchronizes $this/Player with server.
     * @param $db PDO Valid PDO of the database connection.
     * @return null|_Player Returns _Player object on success; NULL on failure.
     */
    public function updatePlayer(PDO $db)
    {
        $stmt = $db->prepare('SELECT * FROM players WHERE community_identifier=? AND steam_identifier=? LIMIT 1');
        $stmt->bindParam(1, $this->community_identifier, PDO::PARAM_STR, MAX_COMMUNITY_ID_LENGTH);
        $stmt->bindParam(2, $this->steam_identifier, PDO::PARAM_STR, MAX_STEAM_ID_LENGTH);
        $success = $stmt->execute();
        if (!$success) {
            throw new PDOException('Failed to execute SQL query.');
        }
        $player = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, '_Player')[0];
        $this->setPlayer($player);
        return $player;
    }
}
