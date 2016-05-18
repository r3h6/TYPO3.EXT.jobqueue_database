<?php

namespace R3H6\JobqueueDatabase\Tests\Functional\Queue;

/*                                                                        *
 * This script is part of the TYPO3 project - inspiring people to share!  *
 *                                                                        *
 * TYPO3 is free software; you can redistribute it and/or modify it under *
 * the terms of the GNU General Public License version 3 as published by  *
 * the Free Software Foundation.                                          *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General      *
 * Public License for more details.                                       *
 *                                                                        */

use DateTime;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use R3H6\JobqueueDatabase\Queue\DatabaseQueue;
use R3H6\Jobqueue\Queue\Message;

/**
 * Functional test case for the DatabaseQueue.
 */
class DatabaseQueueTest extends \TYPO3\CMS\Core\Tests\FunctionalTestCase
{
    use \R3H6\JobqueueDatabase\Tests\Functional\BasicFrontendEnvironmentTrait;
    use \R3H6\Jobqueue\Tests\Functional\Queue\QueueTestTrait;
    use \R3H6\Jobqueue\Tests\Functional\Queue\QueueDelayTestTrait;

    const TABLE = 'tx_jobqueuedatabase_domain_model_job';
    const JOBS_FIXTURES = 'typo3conf/ext/jobqueue_database/Tests/Functional/Fixtures/Database/jobs.xml';
    const QUEUE_NAME = 'TestQueue';

    protected $coreExtensionsToLoad = array('extbase');
    protected $testExtensionsToLoad = array('typo3conf/ext/jobqueue', 'typo3conf/ext/jobqueue_database');

    /**
     * @var TYPO3\CMS\Extbase\Object\ObjectManager
     */
    protected $objectManager;

    /**
     * @var R3H6\JobqueueDatabase\Queue\DatabaseQueue
     */
    protected $queue;

    public function setUp()
    {
        parent::setUp();
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->queue = $this->objectManager->get(DatabaseQueue::class, self::QUEUE_NAME, ['timeout' => 0]);

        $this->setUpBasicFrontendEnvironment();
    }

    public function tearDown()
    {
        parent::tearDown();
        unset($this->queue, $this->objectManager);
    }

    /**
     * @test
     */
    public function publishMessageAndCheckDatabaseRecordAndMessageState()
    {
        $payload = 'TYPO3' . uniqid();
        $newMessage = new Message($payload);
        $this->queue->publish($newMessage);
        $record = $this->getDatabaseConnection()->exec_SELECTgetSingleRow('queue_name, payload, state, attemps, starttime', self::TABLE, '');
        $this->assertSame([
            'queue_name' => self::QUEUE_NAME,
            'payload' => $payload,
            'state' => ''.Message::STATE_PUBLISHED,
            'attemps' => '0',
            'starttime' => '0',
        ], $record, 'Invalid database record');
        $this->assertSame(Message::STATE_PUBLISHED, $newMessage->getState());
    }

    /**
     * @test
     * @depends publishMessageAndCheckDatabaseRecordAndMessageState
     */
    public function waitAndReserve()
    {
        $this->importDataSet(ORIGINAL_ROOT . self::JOBS_FIXTURES);

        $message = $this->queue->waitAndReserve();
        $this->assertInstanceOf(Message::class, $message, 'Not a message!');
        $this->assertSame(4, $message->getIdentifier(), 'Wrong job found in queue!');

        $message = $this->queue->waitAndReserve();
        $this->assertInstanceOf(Message::class, $message, 'Not a message!');
        $this->assertSame(5, $message->getIdentifier(), 'Wrong job found in queue!');

        $message = $this->queue->waitAndReserve();
        $this->assertSame(null, $message, 'There should be no jobs at this moment!');
    }



    /**
     * @test
     */
    public function finishMessage()
    {
        $this->importDataSet(ORIGINAL_ROOT . self::JOBS_FIXTURES);
        $message = new Message('', 1);
        $this->queue->finish($message);
        $this->assertSame(Message::STATE_DONE, $message->getState(), 'Message is not of state done!');
        $this->assertSame(0, $this->getDatabaseConnection()->exec_SELECTcountRows('*', self::TABLE, 'uid=1'), 'Job was not deleted in database!');
    }

    /**
     * @test
     */
    public function countJobs()
    {
        $this->importDataSet(ORIGINAL_ROOT . self::JOBS_FIXTURES);
        $this->assertSame(4, $this->queue->count());
    }

    /**
     * @test
     */
    public function peekFirstTwo()
    {
        $this->importDataSet(ORIGINAL_ROOT . self::JOBS_FIXTURES);
        $messages = $this->queue->peek(2);
        $this->assertCount(2, $messages, 'There should be only two messages');
    }
}
