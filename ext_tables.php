<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Jobqueue Database');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_jobqueuedatabase_domain_model_job', 'EXT:jobqueue_database/Resources/Private/Language/locallang_csh_tx_jobqueuedatabase_domain_model_job.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_jobqueuedatabase_domain_model_job');
$GLOBALS['TCA']['tx_jobqueuedatabase_domain_model_job'] = array(
    'ctrl' => array(
        'title' => 'LLL:EXT:jobqueue_database/Resources/Private/Language/locallang_db.xlf:tx_jobqueuedatabase_domain_model_job',
        'label' => 'queue_name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => true,

        'enablecolumns' => array(

        ),
        'searchFields' => 'queue_name,payload,state,attemps,starttime,tstamp,',
        'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY).'Configuration/TCA/Job.php',
        'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY).'Resources/Public/Icons/tx_jobqueuedatabase_domain_model_job.gif',
    ),
);
