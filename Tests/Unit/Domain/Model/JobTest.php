<?php

namespace R3H6\JobqueueDatabase\Tests\Unit\Domain\Model;

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
 * Test case for class \R3H6\JobqueueDatabase\Domain\Model\Job.
 */
class JobTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \R3H6\JobqueueDatabase\Domain\Model\Job
     */
    protected $subject = null;

    protected function setUp()
    {
        $this->subject = new \R3H6\JobqueueDatabase\Domain\Model\Job();
    }

    protected function tearDown()
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function getQueueNameReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getQueueName()
        );
    }

    /**
     * @test
     */
    public function setQueueNameForStringSetsQueueName()
    {
        $this->subject->setQueueName('Conceived at T3CON10');

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'queueName',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getPayloadReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getPayload()
        );
    }

    /**
     * @test
     */
    public function setPayloadForStringSetsPayload()
    {
        $this->subject->setPayload('Conceived at T3CON10');

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'payload',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getStateReturnsInitialValueForInteger()
    {
        $this->assertSame(
            0,
            $this->subject->getState()
        );
    }

    /**
     * @test
     */
    public function setStateForIntegerSetsState()
    {
        $this->subject->setState(12);

        $this->assertAttributeEquals(
            12,
            'state',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getAttempsReturnsInitialValueForInteger()
    {
        $this->assertSame(
            0,
            $this->subject->getAttemps()
        );
    }

    /**
     * @test
     */
    public function setAttempsForIntegerSetsAttemps()
    {
        $this->subject->setAttemps(12);

        $this->assertAttributeEquals(
            12,
            'attemps',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getStarttimeReturnsInitialValueForDateTime()
    {
        $this->assertEquals(
            null,
            $this->subject->getStarttime()
        );
    }

    /**
     * @test
     */
    public function setStarttimeForDateTimeSetsStarttime()
    {
        $dateTimeFixture = new \DateTime();
        $this->subject->setStarttime($dateTimeFixture);

        $this->assertAttributeEquals(
            $dateTimeFixture,
            'starttime',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getTstampReturnsInitialValueForDateTime()
    {
        $this->assertEquals(
            null,
            $this->subject->getTstamp()
        );
    }

    /**
     * @test
     */
    public function setTstampForDateTimeSetsTstamp()
    {
        $dateTimeFixture = new \DateTime();
        $this->subject->setTstamp($dateTimeFixture);

        $this->assertAttributeEquals(
            $dateTimeFixture,
            'tstamp',
            $this->subject
        );
    }
}
