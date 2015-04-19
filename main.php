<?php
error_reporting(E_ALL);
if (!isset($_POST) || strtoupper($_SERVER['REQUEST_METHOD']) != 'POST') {
    exit('Invalid HTTP request.');
}
if (!isset($_POST['do']) || empty($_POST['do']) || !is_string($_POST['do'])) {
    exit('do is undefined.');
}
require_once('Constants.php');
require_once('Utility.php');
$community_identifier = null;
$name = null;
$steam_identifier = null;
function pass01(&$community_identifier, &$name, &$steam_identifier)
{
    if (!isset($_POST['community_identifier']) || empty($_POST['community_identifier']) || !is_numeric($_POST['community_identifier'])) {
        exit('community_identifier is undefined.');
    }
    if (!isset($_POST['name']) || empty($_POST['name']) || !is_string($_POST['name'])) {
        exit('name is undefined.');
    }
    $name = Utility::sanitizeInput($_POST['name']);
    if (!Utility::isValidName($name)) {
        exit('name is not valid.');
    }
    $community_identifier = strtoupper($_POST['community_identifier']);
    if (!Utility::isValidCommunityId($community_identifier)) {
        exit('community_identifier is not valid.');
    }
    $steam_identifier = Utility::communityId2steamId($community_identifier);
    if (!Utility::isValidSteamId($steam_identifier)) {
        exit('steam_identifier is not valid.');
    }
}

switch (strtoupper($_POST['do'])) {
    case 'LOGIN':
        pass01($community_identifier, $name, $steam_identifier);
        try {
            $db = new PDO(DATABASE_DNS, DATABASE_USERNAME, DATABASE_PASSWD, DATABASE_OPTIONS);
            $player = new Player($community_identifier, $name, $steam_identifier);
            $player->login($db);
            unset($player);
            unset($db);
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
        break;
    case 'LOGOUT':
        pass01($community_identifier, $name, $steam_identifier);
        try {
            $db = new PDO(DATABASE_DNS, DATABASE_USERNAME, DATABASE_PASSWD, DATABASE_OPTIONS);
            $player = new Player($community_identifier, $name, $steam_identifier);
            $player->logout($db);
            unset($player);
            unset($db);
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
        break;
    case 'REGISTER':
        pass01($community_identifier, $name, $steam_identifier);
        try {
            $db = new PDO(DATABASE_DNS, DATABASE_USERNAME, DATABASE_PASSWD, DATABASE_OPTIONS);
            $player = new Player($community_identifier, $name, $steam_identifier);
            $player->register($db);
            unset($player);
            unset($db);
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
        break;
    default:
        exit('do is not valid.');
}