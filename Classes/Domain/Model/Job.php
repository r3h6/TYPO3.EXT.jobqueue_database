<?php

namespace TYPO3\JobqueueDatabase\Domain\Model;

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

/**
 * Jobs.
 */
class Job extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * Queue name.
     *
     * @var string
     */
    protected $queueName = '';

    /**
     * Payload.
     *
     * @var string
     */
    protected $payload = '';

    /**
     * State.
     *
     * @var int
     */
    protected $state = 0;

    /**
     * Attemps.
     *
     * @var int
     */
    protected $attemps = 0;

    /**
     * Starttime.
     *
     * @var \DateTime
     */
    protected $starttime = null;

    /**
     * Timestamp.
     *
     * @var \DateTime
     */
    protected $tstamp = null;

    /**
     * Returns the queueName.
     *
     * @return string $queueName
     */
    public function getQueueName()
    {
        return $this->queueName;
    }

    /**
     * Sets the queueName.
     *
     * @param string $queueName
     */
    public function setQueueName($queueName)
    {
        $this->queueName = $queueName;
    }

    /**
     * Returns the payload.
     *
     * @return string $payload
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * Sets the payload.
     *
     * @param string $payload
     */
    public function setPayload($payload)
    {
        $this->payload = $payload;
    }

    /**
     * Returns the state.
     *
     * @return int $state
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Sets the state.
     *
     * @param int $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * Returns the attemps.
     *
     * @return int $attemps
     */
    public function getAttemps()
    {
        return $this->attemps;
    }

    /**
     * Sets the attemps.
     *
     * @param int $attemps
     */
    public function setAttemps($attemps)
    {
        $this->attemps = $attemps;
    }

    /**
     * Returns the starttime.
     *
     * @return \DateTime $starttime
     */
    public function getStarttime()
    {
        return $this->starttime;
    }

    /**
     * Sets the starttime.
     *
     * @param \DateTime $starttime
     */
    public function setStarttime(\DateTime $starttime = null)
    {
        $this->starttime = $starttime;
    }

    /**
     * Returns the tstamp.
     *
     * @return \DateTime $tstamp
     */
    public function getTstamp()
    {
        return $this->tstamp;
    }

    /**
     * Sets the tstamp.
     *
     * @param \DateTime $tstamp
     */
    public function setTstamp(\DateTime $tstamp = null)
    {
        $this->tstamp = $tstamp;
    }
}
