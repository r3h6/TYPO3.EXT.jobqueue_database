<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

if (!is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['jobqueue'])) {
    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['jobqueue'] = [];
}

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['jobqueue']['R3H6\\JobqueueDatabase\\Queue\\DatabaseQueue'] = [];
