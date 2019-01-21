<?php
return array(
    'ctrl' => array(
        'title' => 'LLL:EXT:jobqueue_database/Resources/Private/Language/locallang_db.xlf:tx_jobqueuedatabase_domain_model_job',
        'label' => 'tstamp',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'dividers2tabs' => true,
        'hideTable' => true,
        'enablecolumns' => array(

        ),
        'searchFields' => 'uid,queue_name,state,attemps,starttime,tstamp,',
        'iconfile' => 'EXT:jobqueue_database/Resources/Public/Icons/tx_jobqueuedatabase_domain_model_job.gif',
        'readOnly' => true,
        'rootLevel' => 1,
    ),
    'interface' => array(
        'showRecordFieldList' => 'queue_name, payload, state, attemps, starttime, tstamp',
    ),
    'types' => array(
        '1' => array('showitem' => 'queue_name, payload, state, attemps, starttime, tstamp, '),
    ),
    'palettes' => array(
        '1' => array('showitem' => ''),
    ),
    'columns' => array(

        'queue_name' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:jobqueue_database/Resources/Private/Language/locallang_db.xlf:tx_jobqueuedatabase_domain_model_job.queue_name',
            'config' => array(
                'readOnly' => true,
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ),
        ),
        'payload' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:jobqueue_database/Resources/Private/Language/locallang_db.xlf:tx_jobqueuedatabase_domain_model_job.payload',
            'config' => array(
                'readOnly' => true,
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
            ),
        ),
        'state' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:jobqueue_database/Resources/Private/Language/locallang_db.xlf:tx_jobqueuedatabase_domain_model_job.state',
            'config' => array(
                'readOnly' => true,
                'type' => 'input',
                'size' => 4,
                'eval' => 'int',
            ),
        ),
        'attemps' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:jobqueue_database/Resources/Private/Language/locallang_db.xlf:tx_jobqueuedatabase_domain_model_job.attemps',
            'config' => array(
                'readOnly' => true,
                'type' => 'input',
                'size' => 4,
                'eval' => 'int',
            ),
        ),
        'starttime' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:jobqueue_database/Resources/Private/Language/locallang_db.xlf:tx_jobqueuedatabase_domain_model_job.starttime',
            'config' => array(
                'readOnly' => true,
                'type' => 'input',
                'size' => 10,
                'eval' => 'datetime',
                'checkbox' => 1,
                'default' => time(),
            ),
        ),
        'tstamp' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:jobqueue_database/Resources/Private/Language/locallang_db.xlf:tx_jobqueuedatabase_domain_model_job.tstamp',
            'config' => array(
                'readOnly' => true,
                'type' => 'input',
                'size' => 10,
                'eval' => 'datetime',
                'checkbox' => 1,
                'default' => time(),
            ),
        ),

    ),
);
