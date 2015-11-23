<?php
namespace TYPO3\JobqueueDatabase\Tests\Functional;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Page\PageRepository;

/**
 * BasicFrontendEnvironmentTrait
 */
trait BasicFrontendEnvironmentTrait {
	/**
	 * Minimal frontent environment to satisfy Extbase Typo3DbBackend
	 */
	protected function setUpBasicFrontendEnvironment() {
		$environmentServiceMock = $this->getMock('TYPO3\\CMS\\Extbase\\Service\\EnvironmentService');
		$environmentServiceMock
			->expects($this->any())
			->method('isEnvironmentInFrontendMode')
			->willReturn(TRUE);
		GeneralUtility::setSingletonInstance('TYPO3\\CMS\\Extbase\\Service\\EnvironmentService', $environmentServiceMock);

		$pageRepositoryFixture = new PageRepository();
		$frontendControllerMock = $this->getMock('TYPO3\\CMS\\Frontend\\Controller\\TypoScriptFrontendController', array(), array(), '', FALSE);
		$frontendControllerMock->sys_page = $pageRepositoryFixture;
		$GLOBALS['TSFE'] = $frontendControllerMock;
	}

	protected $disallowChangesToGlobalState = null;

	public function setDisallowChangesToGlobalState($disallowChangesToGlobalState){
		if (is_null($this->disallowChangesToGlobalState) && is_bool($disallowChangesToGlobalState)) {
			$this->disallowChangesToGlobalState = $disallowChangesToGlobalState;
		}
	}
}