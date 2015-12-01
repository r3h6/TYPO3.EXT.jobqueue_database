<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$GLOBALS['TYPO3_CONF_VARS']['EXT']['jobqueue']['TYPO3\\JobqueueDatabase\\Queue\\DatabaseQueue'] = [];
