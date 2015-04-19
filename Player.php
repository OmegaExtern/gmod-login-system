<?php
require_once('Constants.php');
require_once('Utility.php');

abstract class _Player
{
    public $identifier, $community_identifier, $name, $steam_identifier, $banned, $banned_date_time, $banned_expire_date_time, $banned_reason, $date_time, $experience, $joined_date_time, $joined_name, $level, $old_name, $online, $points, $rank, $warning_percentage;
}

class Player extends _Player
{
    public function Player($community_identifier, $name, $steam_identifier)
    {
        $this->community_identifier = $community_identifier;
        $this->name = $name;
        $this->steam_identifier = $steam_identifier;
    }

    /**
     * @param $db PDO PDO of the database connection.
     * @param $community_identifier int Community ID of the player to check.
     * @return bool Returns TRUE if the given community ID exists; otherwise FALSE.
     */
    public static function communityIdExists($db, $community_identifier)
    {
        $stmt = $db->prepare('SELECT 1 FROM players WHERE community_identifier=? LIMIT 1');
        $stmt->bindParam(1, $community_identifier, PDO::PARAM_INT, MAX_COMMUNITY_ID_LENGTH);
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
     */
    public static function nameExists($db, $name)
    {
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
     */
    public static function steamIdExists($db, $steam_identifier)
    {
        $stmt = $db->prepare('SELECT 1 FROM players WHERE steam_identifier=? LIMIT 1');
        $stmt->bindParam(1, $steam_identifier, PDO::PARAM_STR, MAX_STEAM_ID_LENGTH);
        $success = $stmt->execute();
        if (!$success) {
            throw new PDOException('Failed to execute SQL query.');
        }
        return $stmt->rowCount() > 0;
    }

    public static function getPlayerByCommunityId($db, $communityId)
    {
    }

    public static function getPlayerByName($db, $name)
    {
    }

    public static function getPlayerBySteamId($db, $steamId)
    {
    }

    /**
     * @param $player _Player _Player object.
     */
    public static function printPlayer($player)
    {
        echo 'return {
identifier = ' . $player->identifier . ',
community_identifier = ' . $player->community_identifier . ',
name = "' . $player->name . '",
steam_identifier = "' . $player->steam_identifier . '",
banned = ' . $player->banned . ',
banned_date_time = "' . $player->banned_date_time . '",
banned_expire_date_time = "' . $player->banned_expire_date_time . '",
banned_reason = "' . $player->banned_reason . '",
date_time = "' . $player->date_time . '",
experience = ' . $player->experience . ',
joined_date_time = "' . $player->joined_date_time . '",
joined_name = "' . $player->joined_name . '",
level = ' . $player->level . ',
old_name = "' . $player->old_name . '",
online = ' . $player->online . ',
points = ' . $player->points . ',
rank = "' . $player->rank . '",
warning_percentage = ' . $player->warning_percentage . '
}';
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
        $stmt->bindParam(1, $this->community_identifier, PDO::PARAM_INT, MAX_COMMUNITY_ID_LENGTH);
        $stmt->bindParam(2, $this->$name, PDO::PARAM_STR, MAX_NAME_LENGTH);
        $stmt->bindParam(3, $this->$steam_identifier, PDO::PARAM_STR, MAX_STEAM_ID_LENGTH);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE);
        $success = $stmt->execute();
        if (!$success) {
            throw new PDOException('Failed to execute SQL query.');
        }
        if ($stmt->rowCount() < 1) {
            exit('invalid credentials.');
        }
        $player = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "_Player")[0];
        if ($player->banned) {
            exit('player is banned.');
        }
        if ($player->online) {
            exit('player is already online.');
        }
        $stmt = $db->prepare('UPDATE players SET online=? WHERE identifier=?');
        $stmt->bindValue(1, true);
        $stmt->bindParam(2, $player->identifier, PDO::PARAM_INT, MAX_IDENTIFIER_LENGTH);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE);
        $success = $stmt->execute();
        if (!$success) {
            throw new PDOException('Failed to execute SQL query.');
        }
        $player = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "_Player")[0];
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
        $stmt->bindParam(1, $this->$community_identifier, PDO::PARAM_INT, MAX_COMMUNITY_ID_LENGTH);
        $stmt->bindParam(2, $this->$name, PDO::PARAM_STR, MAX_NAME_LENGTH);
        $stmt->bindParam(3, $this->$name, PDO::PARAM_STR, MAX_NAME_LENGTH);
        $stmt->bindParam(4, $this->$name, PDO::PARAM_STR, MAX_NAME_LENGTH);
        $stmt->bindParam(5, $this->$steam_identifier, PDO::PARAM_STR, MAX_STEAM_ID_LENGTH);
        $success = $stmt->execute();
        if (!$success) {
            throw new PDOException('Failed to execute SQL query.');
        }
    }

    /**
     * @param $db PDO PDO of the database connection.
     * @param $array array Key-value pair to update.
     */
    public function update($db, $array) {
    }
}