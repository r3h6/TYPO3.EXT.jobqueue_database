<?php

namespace R3H6\JobqueueDatabase\Domain\Repository;

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

use R3H6\Jobqueue\Queue\Message;
use R3H6\JobqueueDatabase\Domain\Model\Job as DatabaseJob;
use TYPO3\CMS\Extbase\Persistence\Generic\Query;

/**
 * The repository for Jobs.
 */
class JobRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    protected $defaultOrderings = array(
        'uid' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING,
    );

    protected $table = 'tx_jobqueuedatabase_domain_model_job';

    public function initializeObject()
    {
        /** @var $querySettings \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings */
        $querySettings = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);
    }

    /**
     * Finds the next job in the queue.
     *
     * WARNING: Other workers may found the same job.
     *          Thread savety is handled by the database queue.
     *
     * @param $queueName
     * @return \R3H6\JobqueueDatabase\Domain\Model\Job
     */
    public function findNextOneByQueueName($queueName)
    {
        /** @var TYPO3\CMS\Extbase\Persistence\Generic\Query $query */
        $query = $this->createQuery();
        $this->createConstraint($query, $queueName);
        return $query->execute()->getFirst();
    }

    /**
     * Finds the next few published jobs in the queue.
     *
     * WARNING: Other workers may found the same jobs.
     *          Thread savety is handled by the database queue.
     *
     * @param  string  $queueName
     * @param  integer $limit
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findNextByQueueName($queueName, $limit = 1)
    {
        /** @var TYPO3\CMS\Extbase\Persistence\Generic\Query $query */
        $query = $this->createQuery();
        $this->createConstraint($query, $queueName);

        if ($limit < 1) {
            throw new InvalidArgumentException('Limit can not be less than one!', 1448306118);
        }

        if ($limit) {
            $query->setLimit($limit);
        }

        return $query->execute();
    }

    protected function createConstraint(Query $query, $queueName)
    {
        $constraints = array();
        $constraints[] = $query->equals('queueName', $queueName);
        $constraints[] = $query->equals('state', Message::STATE_PUBLISHED);
        $constraints[] = $query->logicalOr(
            $query->equals('starttime', 0),
            $query->lessThanOrEqual('starttime', time())
        );
        $query->matching(
            $query->logicalAnd($constraints)
        );
    }

    /**
     * Tries to reserve a job.
     *
     * @param  DatabaseJob $job
     * @return boolean true if job could be reserved
     */
    public function reserve(DatabaseJob $job)
    {
        $job->setState(Message::STATE_RESERVED);
        $where = 'state!='.(int) Message::STATE_RESERVED.' AND uid='.(int) $job->getUid();
        $fields = ['state' => Message::STATE_RESERVED];
        $this->getDatabaseConnection()->exec_UPDATEquery($this->table, $where, $fields);

        return ($this->getDatabaseConnection()->sql_affected_rows() === 1);
    }

    /**
     * @return \TYPO3\CMS\Core\Database\DatabaseConnection
     */
    protected function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }
}
