<?php

/***************************************************************
 * Extension Manager/Repository config file for ext: "jobqueue_database"
 *
 * Auto generated by Extension Builder 2015-11-19
 *
 * Manual updates:
 * Only the data in the array - anything else is removed by next write.
 * "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
    'title' => 'Jobqueue Database',
    'description' => 'Database implementation of the jobqueue.',
    'category' => 'services',
    'author' => 'R3H6',
    'author_email' => 'r3h6@outlook.com',
    'state' => 'beta',
    'internal' => '',
    'uploadfolder' => '0',
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'version' => '0.0.1',
    'constraints' => array(
        'depends' => array(
            'typo3' => '6.2',
            'jobqueue' => '0.0.1',
        ),
        'conflicts' => array(
        ),
        'suggests' => array(
        ),
    ),
);
