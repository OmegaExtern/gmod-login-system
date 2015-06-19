<?php
/**
 * Constants.php
 * @license The MIT License (MIT) < http://opensource.org/licenses/MIT >
 * @author OmegaExtern < https://github.com/OmegaExtern > < omegaextern@live.com >
 */

const COMMUNITY_ID_REGEX = '^(7656119[0-9]{10})$';
const DATABASE_DNS = 'mysql:host=127.0.0.1;port=3306;dbname=gmod_login_system;charset=utf8;';
const DATABASE_OPTIONS = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'];
const DATABASE_PASSWD = '';
const DATABASE_USERNAME = 'root';
const MAX_COMMUNITY_ID_LENGTH = 17;
const MAX_IDENTIFIER_LENGTH = 20;
const MAX_NAME_LENGTH = 24;
const MAX_STEAM_ID_LENGTH = 19;
const MIN_NAME_LENGTH = 4;
const NAME_REGEX = '^([A-Za-z][0-9A-Za-z]{' . (MIN_NAME_LENGTH - 1) . ',' . (MAX_NAME_LENGTH - 1) . '})$';
const STEAM_ID_REGEX = '^(STEAM_0:[0-1]:[0-9]{1,9})$';
