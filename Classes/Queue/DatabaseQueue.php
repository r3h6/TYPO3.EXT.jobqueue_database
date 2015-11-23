<?php
namespace TYPO3\JobqueueDatabase\Queue;

use TYPO3\JobqueueDatabase\Domain\Model\Job as DatabaseJob;
use TYPO3\Jobqueue\Queue\Message;
use TYPO3\Jobqueue\Queue\QueueInterface;

class DatabaseQueue implements QueueInterface {

	/**
	 * [$jobRepository description]
	 * @var TYPO3\JobqueueDatabase\Domain\Repository\JobRepository
	 * @inject
	 */
	protected $jobRepository = NULL;

	/**
	 * PersistenceManager
	 * @var TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
	 * @inject
	 */
	protected $persistenceManager = NULL;

	protected $name;

	public function __construct($name, $options){
		$this->name = $name;
		$this->options = $options;
	}

	public function publish(Message $message){
		$job = $this->encodeJob($message);
		$job->setState(Message::STATE_PUBLISHED);
		$this->jobRepository->add($job);
		$this->persistenceManager->persistAll();
		$message->setState($job->getState());
	}

	public function waitAndTake($timeout = NULL){
		$job = $this->jobRepository->findNextOneByQueueName($this->name);
		if ($job !== NULL){
			$message = $this->decodeJob($job);
			$this->finish($message);
			return $message;
		} else if ($timeout !== NULL) {
			sleep($timeout);
			return $this->waitAndReserve();
		}
		return NULL;
	}

	public function waitAndReserve($timeout = NULL){
		$job = $this->jobRepository->findNextOneByQueueName($this->name);
		if ($job !== NULL){
			$job->setState(Message::STATE_RESERVED);
			$this->jobRepository->update($job);
			$this->persistenceManager->persistAll();
			return $this->decodeJob($job);
		} else if ($timeout !== NULL) {
			sleep($timeout);
			return $this->waitAndReserve();
		}
		return NULL;
	}

	public function finish(Message $message){
		$job = $this->jobRepository->findByUid($message->getIdentifier());
		$job->setState(Message::STATE_DONE);
		$this->jobRepository->remove($job);
		$this->persistenceManager->persistAll();
		$message->setState($job->getState());
		return TRUE;
	}

	public function peek($limit = 1){
		$messages = [];
		$jobs = $this->jobRepository->findNextByQueueName($this->name, $limit);
		foreach ($jobs as $job){
			$messages[] = $this->decodeJob($job);
		}
		return $messages;
	}

	public function getMessage($identifier){
		$job = $this->findByUid($identifier);
		if ($job){
			return $this->decodeJob($job);
		}
		return NULL;
	}

	public function count(){
		return $this->jobRepository->countByQueueName($this->name);
	}

	public function getOptions (){
		return $this->options;
	}

	public function getName (){
		return $this->name;
	}

	private function encodeJob (Message $message){
		$job = new DatabaseJob();
		$job->setQueueName($this->name);
		$job->setPayload($message->getPayload());
		$job->setAttemps($message->getAttemps());
		$job->setState($message->getState());
		$job->setStarttime($message->getAvailableAt());
		return $job;
	}

	private function decodeJob (DatabaseJob $job){
		$message = new Message(
			$job->getPayload(),
			$job->getUid()
		);
		$message->setState($job->getState());
		$message->setAttemps($job->getAttemps());
		return $message;
	}
}