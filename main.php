<?php
/**
 * main.php
 * @license The MIT License (MIT) < http://opensource.org/licenses/MIT >
 * @author OmegaExtern < https://github.com/OmegaExtern > < omegaextern@live.com >
 */

// error_reporting(E_ALL);
// date_default_timezone_set('Europe/Zagreb');
if (!isset($_POST) || strtoupper($_SERVER['REQUEST_METHOD']) != 'POST') {
    exit('error: Invalid HTTP request.');
}
if (!isset($_POST['do']) || empty($_POST['do']) || !is_string($_POST['do'])) {
    exit('error: do is undefined.');
}
require_once('Constants.php');
require_once('Player.php');
require_once('Utility.php');
$community_identifier = null;
$steam_identifier = null;
function pass01(&$community_identifier, &$steam_identifier)
{
    if (!isset($_POST['community_identifier']) || empty($_POST['community_identifier']) || !is_numeric($_POST['community_identifier'])) {
        exit('error: community_identifier is undefined.');
    }
    $community_identifier = strtoupper($_POST['community_identifier']);
    $steam_identifier = Utility::communityId2steamId($community_identifier);
}

switch (strtoupper($_POST['do'])) {
    case 'LOGIN':
        pass01($community_identifier, $steam_identifier);
        try {
            $db = new PDO(DATABASE_DNS, DATABASE_USERNAME, DATABASE_PASSWD, DATABASE_OPTIONS);
            $player = new Player();
            $player->community_identifier = $community_identifier;
            $player->steam_identifier = $steam_identifier;
            $player->connect($db);
            unset($player);
            unset($db);
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
        break;
    case 'LOGOUT':
        pass01($community_identifier, $steam_identifier);
        try {
            $db = new PDO(DATABASE_DNS, DATABASE_USERNAME, DATABASE_PASSWD, DATABASE_OPTIONS);
            $player = new Player();
            $player->community_identifier = $community_identifier;
            $player->steam_identifier = $steam_identifier;
            $player->connect($db, false);
            unset($player);
            unset($db);
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
        break;
    case 'REGISTER':
        pass01($community_identifier, $steam_identifier);
        if (!isset($_POST['name']) || empty($_POST['name']) || !is_string($_POST['name'])) {
            exit('error: name is undefined.');
        }
        $name = Utility::sanitizeInput($_POST['name']);
        if (!Utility::isValidName($name)) {
            exit('error: name is not valid.');
        }
        try {
            $db = new PDO(DATABASE_DNS, DATABASE_USERNAME, DATABASE_PASSWD, DATABASE_OPTIONS);
            $player = new Player();
            $player->community_identifier = $community_identifier;
            $player->name = $name;
            $player->steam_identifier = $steam_identifier;
            $player->register($db);
            unset($player);
            unset($db);
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
        break;
    default:
        exit('error: do is not valid.');
}
