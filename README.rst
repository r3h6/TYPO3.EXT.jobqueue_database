.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.


.. _start:

=============
Documentation
=============

Job queues for TYPO3 CMS. Implements concrete queue for the database workqueue. Requires the exension *jobqueue* to be installed.


Configuration
-------------

In order to use this queue you should set the *defaultQueue* to ``TYPO3\JobqueueDatabase\Queue\DatabaseQueue`` in the *jobqueue* extension settings.