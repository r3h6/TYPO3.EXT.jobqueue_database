<?php

namespace TYPO3\JobqueueDatabase\Domain\Repository;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2015 R3H6 <r3h6@outlook.com>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use TYPO3\Jobqueue\Queue\Message;
use TYPO3\JobqueueDatabase\Domain\Model\Job as DatabaseJob;
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
     * @param $queueName
     */
    public function findNextOneByQueueName($queueName)
    {
        /** @var TYPO3\CMS\Extbase\Persistence\Generic\Query $query */
        $query = $this->createQuery();
        $this->createConstraint($query, $queueName);
        return $query->execute()->getFirst();
    }

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
