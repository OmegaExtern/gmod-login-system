<?php
require_once('Constants.php');
require_once('Utility.php');

/**
 * Class _Player
 */
class _Player
{
    public $identifier, $community_identifier, $name, $steam_identifier, $banned, $banned_date_time, $banned_expire_date_time, $banned_reason, $date_time, $experience, $joined_date_time, $joined_name, $level, $old_name, $online, $points, $rank, $warning_percentage;
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
     * @param $db PDO PDO of the database connection.
     * @param $community_identifier string Community ID of the player to check.
     * @return bool Returns TRUE if the given community ID exists; otherwise FALSE.
     * @throws Exception
     */
    public static function communityIdExists($db, $community_identifier)
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
     * @param $db PDO PDO of the database connection.
     * @param $name string Name of the player to check.
     * @return bool Returns TRUE if the given name exists; otherwise FALSE.
     * @throws Exception
     */
    public static function nameExists($db, $name)
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
     * @param $db PDO PDO of the database connection.
     * @param $steam_identifier string Steam ID of the player to check.
     * @return bool Returns TRUE if the given Steam ID exists; otherwise FALSE.
     * @throws Exception
     */
    public static function steamIdExists($db, $steam_identifier)
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
     * @param $db PDO PDO of the database connection.
     * @param $community_identifier string Community ID of the player.
     * @param $name string Name of the player.
     * @param $steam_identifier string Steam ID of the player.
     * @return _Player _Player object.
     * @throws Exception
     */
    public static function getUpdatedPlayer($db, $community_identifier, $name, $steam_identifier) {
        if (!Utility::isValidCommunityId($community_identifier)) {
            throw new Exception('community_identifier is not valid.');
        }
        if (!Utility::isValidName($name)) {
            throw new Exception('name is not valid.');
        }
        if (!Utility::isValidSteamId($steam_identifier)) {
            throw new Exception('steam_identifier is not valid.');
        }
        $stmt = $db->prepare('SELECT * FROM players WHERE community_identifier=? AND name=? AND steam_identifier=? LIMIT 1');
        $stmt->bindParam(1, $community_identifier, PDO::PARAM_STR, MAX_COMMUNITY_ID_LENGTH);
        $stmt->bindParam(2, $name, PDO::PARAM_STR, MAX_NAME_LENGTH);
        $stmt->bindParam(3, $steam_identifier, PDO::PARAM_STR, MAX_STEAM_ID_LENGTH);
        $stmt->execute();
        $player = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "_Player")[0];
        self::printPlayer($player);
        return $player;
    }

    /**
     * @param $db PDO PDO of the database connection.
     * @param $community_identifier string Community ID of the player.
     * @return null|Player Returns a new Player object on success; NULL on failure.
     * @throws Exception
     */
    public static function getPlayerByCommunityId($db, $community_identifier)
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
        $player = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "_Player")[0];
        $new->setPlayer($player);
        self::printPlayer($player);
        return $new;
    }

    /**
     * @param $db PDO PDO of the database connection.
     * @param $name string Name of the player.
     * @return null|Player Returns a new Player object on success; NULL on failure.
     * @throws Exception
     */
    public static function getPlayerByName($db, $name)
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
        $player = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "_Player")[0];
        $new->setPlayer($player);
        self::printPlayer($player);
        return $new;
    }

    /**
     * @param $db PDO PDO of the database connection.
     * @param $steam_identifier string Steam ID of the player.
     * @return null|Player Returns a new Player object on success; NULL on failure.
     * @throws Exception
     */
    public static function getPlayerBySteamId($db, $steam_identifier)
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
        $player = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "_Player")[0];
        $new->setPlayer($player);
        self::printPlayer($player);
        return $new;
    }

    /**
     * @param $player _Player _Player object.
     */
    public static function printPlayer($player)
    {
        echo 'return {
    ["identifier"] = ' . $player->identifier . ',
    ["community_identifier"] = "' . $player->community_identifier . '",
    ["name"] = "' . $player->name . '",
    ["steam_identifier"] = "' . $player->steam_identifier . '",
    ["banned"] = ' . $player->banned . ',
    ["banned_date_time"] = "' . $player->banned_date_time . '",
    ["banned_expire_date_time"] = "' . $player->banned_expire_date_time . '",
    ["banned_reason"] = "' . $player->banned_reason . '",
    ["date_time"] = "' . $player->date_time . '",
    ["experience"] = ' . $player->experience . ',
    ["joined_date_time"] = "' . $player->joined_date_time . '",
    ["joined_name"] = "' . $player->joined_name . '",
    ["level"] = ' . $player->level . ',
    ["old_name"] = "' . $player->old_name . '",
    ["online"] = ' . $player->online . ',
    ["points"] = ' . $player->points . ',
    ["rank"] = "' . $player->rank . '",
    ["warning_percentage"] = ' . $player->warning_percentage . '
}';
    }

    /**
     * @param $player _Player _Player object.
     */
    public function setPlayer($player) {
        $this->community_identifier = $player->community_identifier;
        $this->name = $player->name;
        $this->steam_identifier = $player->steam_identifier;
        $this->banned = $player->banned;
        $this->banned_date_time = $player->banned_date_time;
        $this->banned_expire_date_time = $player->banned_expire_date_time;
        $this->banned_reason = $player->banned_reason;
        $this->date_time = $player->date_time;
        $this->experience = $player->experience;
        $this->joined_date_time = $player->joined_date_time;
        $this->joined_name = $player->joined_name;
        $this->level = $player->level;
        $this->old_name = $player->old_name;
        $this->online = $player->online;
        $this->points = $player->points;
        $this->rank = $player->rank;
        $this->warning_percentage = $player->warning_percentage;
    }

    /**
     * @param $db PDO PDO of the database connection.
     */
    public function login($db)
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
        $stmt = $db->prepare('SELECT * FROM players WHERE community_identifier=? AND name=? AND steam_identifier=? LIMIT 1');
        $stmt->bindParam(1, $this->community_identifier, PDO::PARAM_STR, MAX_COMMUNITY_ID_LENGTH);
        $stmt->bindParam(2, $this->name, PDO::PARAM_STR, MAX_NAME_LENGTH);
        $stmt->bindParam(3, $this->steam_identifier, PDO::PARAM_STR, MAX_STEAM_ID_LENGTH);
        $success = $stmt->execute();
        if (!$success) {
            throw new PDOException('Failed to execute SQL query.');
        }
        if ($stmt->rowCount() < 1) {
            exit('invalid credentials.');
        }
        $player = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "_Player")[0];
        if (Utility::int2bool($player->banned)) {
            self::printPlayer($player);
            unset($player);
            exit('player is banned.');
        }
        if (Utility::int2bool($player->online)) {
            self::printPlayer($player);
            unset($player);
            exit('player is already online.');
        }
        $stmt = $db->prepare('UPDATE players SET online=? WHERE identifier=?');
        $stmt->bindValue(1, true);
        $stmt->bindParam(2, $player->identifier, PDO::PARAM_STR, MAX_IDENTIFIER_LENGTH);
        $success = $stmt->execute();
        if (!$success) {
            throw new PDOException('Failed to execute SQL query.');
        }
        $player = $this->updatePlayer($db); // self::getUpdatedPlayer($db, $this->community_identifier, $this->name, $this->steam_identifier);
        self::printPlayer($player);
        unset($player);
    }

    /**
     * @param $db PDO PDO of the database connection.
     */
    public function logout($db)
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
        $stmt = $db->prepare('SELECT * FROM players WHERE community_identifier=? AND name=? AND steam_identifier=? LIMIT 1');
        $stmt->bindParam(1, $this->community_identifier, PDO::PARAM_STR, MAX_COMMUNITY_ID_LENGTH);
        $stmt->bindParam(2, $this->name, PDO::PARAM_STR, MAX_NAME_LENGTH);
        $stmt->bindParam(3, $this->steam_identifier, PDO::PARAM_STR, MAX_STEAM_ID_LENGTH);
        $success = $stmt->execute();
        if (!$success) {
            throw new PDOException('Failed to execute SQL query.');
        }
        if ($stmt->rowCount() < 1) {
            exit('invalid credentials.');
        }
        $player = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "_Player")[0];
        if ($player->banned) {
            self::printPlayer($player);
            unset($player);
            exit('player is banned.');
        }
        if (!$player->online) {
            self::printPlayer($player);
            unset($player);
            exit('player is already offline.');
        }
        $stmt = $db->prepare('UPDATE players SET online=? WHERE identifier=?');
        $stmt->bindValue(1, false);
        $stmt->bindParam(2, $player->identifier, PDO::PARAM_STR, MAX_IDENTIFIER_LENGTH);
        $success = $stmt->execute();
        if (!$success) {
            throw new PDOException('Failed to execute SQL query.');
        }
        $player = $this->updatePlayer($db); // self::getUpdatedPlayer($db, $this->community_identifier, $this->name, $this->steam_identifier);
        self::printPlayer($player);
        unset($player);
    }

    /**
     * @param $db PDO PDO of the database connection.
     */
    public function register($db)
    {
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
        $player = $this->updatePlayer($db); // self::getUpdatedPlayer($db, $this->community_identifier, $this->name, $this->steam_identifier);
        self::printPlayer($player);
        unset($player);
    }

    /**
     * @param $db PDO PDO of the database connection.
     * @param $array array Key-value pair to update.
     */
    public function update($db, $array) {
        if (!self::communityIdExists($db, $this->community_identifier)) {
            exit('community_identifier does not exist.');
        }
        if (!self::nameExists($db, $this->name)) {
            exit('name does not exist.');
        }
        if (!self::steamIdExists($db, $this->steam_identifier)) {
            exit('steam_identifier does not exist.');
        }
        $stmt = $db->prepare('SELECT * FROM players WHERE community_identifier=? AND name=? AND steam_identifier=? LIMIT 1');
        $stmt->bindParam(1, $this->community_identifier, PDO::PARAM_STR, MAX_COMMUNITY_ID_LENGTH);
        $stmt->bindParam(2, $this->name, PDO::PARAM_STR, MAX_NAME_LENGTH);
        $stmt->bindParam(3, $this->steam_identifier, PDO::PARAM_STR, MAX_STEAM_ID_LENGTH);
        $success = $stmt->execute();
        if (!$success) {
            throw new PDOException('Failed to execute SQL query.');
        }
        $array_count = count($array);
        if ($array_count < 1) {
            throw new InvalidArgumentException('array does not have any valid key-value pair(s).');
        }
        $player = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "_Player")[0];
        $statement = 'UPDATE players SET';
        foreach ($array as $key => $value) {
            $statement = $statement . ' ' . $key . '=:' . $key;
        }
        $statement = $statement . ' WHERE identifier=:identifier';
        $array = array_merge($array, array(':identifier' => $player->identifier));
        $stmt = $db->prepare($statement);
        $success = $stmt->execute($array);
        if (!$success) {
            throw new PDOException('Failed to execute SQL query.');
        }
        unset($array);
        $player = $this->updatePlayer($db); // self::getUpdatedPlayer($db, $this->community_identifier, $this->name, $this->steam_identifier);
        self::printPlayer($player);
        unset($player);
    }

    /**
     * @param $db PDO PDO of the database connection.
     * @return null|_Player Returns _Player object on success; NULL on failure.
     */
    public function updatePlayer($db) {
        $stmt = $db->prepare('SELECT * FROM players WHERE community_identifier=? AND steam_identifier=? LIMIT 1');
        $stmt->bindParam(1, $this->community_identifier, PDO::PARAM_STR, MAX_COMMUNITY_ID_LENGTH);
        $stmt->bindParam(2, $this->steam_identifier, PDO::PARAM_STR, MAX_STEAM_ID_LENGTH);
        $success = $stmt->execute();
        if (!$success) {
            throw new PDOException('Failed to execute SQL query.');
        }
        $player = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "_Player")[0];
        $this->setPlayer($player);
        return $player;
    }
}