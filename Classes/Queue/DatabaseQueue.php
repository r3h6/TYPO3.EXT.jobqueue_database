<?php

namespace R3H6\JobqueueDatabase\Queue;

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

use R3H6\JobqueueDatabase\Domain\Model\Job as DatabaseJob;
use R3H6\Jobqueue\Queue\Message;
use R3H6\Jobqueue\Queue\QueueInterface;

/**
 * DatabaseQueue
 */
class DatabaseQueue implements QueueInterface
{
    /**
     * @var R3H6\JobqueueDatabase\Domain\Repository\JobRepository
     * @inject
     */
    protected $jobRepository = null;

    /**
     * @var TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
     * @inject
     */
    protected $persistenceManager = null;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $options = [
        'timeout' => null,
    ];

    /**
     * Constructor
     *
     * @param string $name
     * @param array  $options
     */
    public function __construct($name, array $options)
    {
        $this->name = $name;
        $this->options = (array) $options + $this->options;
    }

    /**
     * @param Message $message
     */
    public function publish(Message $message)
    {
        $job = $this->encodeJob($message);
        $job->setState(Message::STATE_PUBLISHED);
        $this->jobRepository->add($job);
        $this->persistenceManager->persistAll();
        $message->setState($job->getState());
    }

    /**
     * @param int $timeout
     * @return Message
     */
    public function waitAndTake($timeout = null)
    {
        if ($timeout === null) {
            $timeout = $this->options['timeout'];
        }
        do {
            $job = $this->jobRepository->findNextOneByQueueName($this->name);
            if ($job !== null) {
                if ($this->jobRepository->reserve($job)) {
                    $message = $this->decodeJob($job);
                    $this->finish($message);
                    return $message;
                }
            }
            if ($timeout === null) {
                sleep(1);
            } elseif ($timeout > 0) {
                sleep($timeout);
                $timeout = 0;
            } else {
                break;
            }
        } while (true);
        return null;
    }

    /**
     * @param int $timeout
     * @return Message
     */
    public function waitAndReserve($timeout = null)
    {
        if ($timeout === null) {
            $timeout = $this->options['timeout'];
        }
        do {
            $job = $this->jobRepository->findNextOneByQueueName($this->name);
            if ($job !== null) {
                if ($this->jobRepository->reserve($job)) {
                    return $this->decodeJob($job);
                }
            }
            if ($timeout === null) {
                sleep(1);
            } elseif ($timeout > 0) {
                sleep($timeout);
                $timeout = 0;
            } else {
                break;
            }
        } while (true);
        return null;
    }

    /**
     * @param Message $message
     */
    public function finish(Message $message)
    {
        $job = $this->jobRepository->findByUid($message->getIdentifier());
        $job->setState(Message::STATE_DONE);
        $this->jobRepository->remove($job);
        $this->persistenceManager->persistAll();
        $message->setState($job->getState());

        return true;
    }

    /**
     * @param int $limit
     * @return array<\R3H6\Jobqueue\Queue\Message>
     */
    public function peek($limit = 1)
    {
        $messages = [];
        $jobs = $this->jobRepository->findNextByQueueName($this->name, $limit);
        foreach ($jobs as $job) {
            $messages[] = $this->decodeJob($job);
        }

        return $messages;
    }

    /**
     * @param string $identifier
     * @return Message
     */
    public function getMessage($identifier)
    {
        $job = $this->findByUid($identifier);
        if ($job) {
            return $this->decodeJob($job);
        }

        return null;
    }

    /**
     * @return int
     */
    public function count()
    {
        return $this->jobRepository->countByQueueName($this->name);
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Converts a message to a data model.
     *
     * @param  Message $message [description]
     * @return R3H6\JobqueueDatabase\Domain\Model\Job
     */
    private function encodeJob(Message $message)
    {
        $job = new DatabaseJob();
        $job->setQueueName($this->name);
        $job->setPayload($message->getPayload());
        $job->setAttemps($message->getAttemps());
        $job->setState($message->getState());
        $job->setStarttime($message->getAvailableAt());

        return $job;
    }

    /**
     * Converts a data model into a message.
     *
     * @param  R3H6\JobqueueDatabase\Domain\Model\Job $job
     * @return Message
     */
    private function decodeJob(DatabaseJob $job)
    {
        $message = new Message(
            $job->getPayload(),
            $job->getUid()
        );
        $message->setState($job->getState());
        $message->setAttemps($job->getAttemps());

        return $message;
    }
}
