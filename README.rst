*****************
Jobqueue Database
*****************

Implements concrete Queue for the database workqueue. Requires the exension *jobqueue* to be installed.



Configuration
-------------

In order to use this queue you should set the *defaultQueue* to ``TYPO3\\JobqueueDatabase\\Queue\\DatabaseQueue`` in the *jobqueue* extension settings.