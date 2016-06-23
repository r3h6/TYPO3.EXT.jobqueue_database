.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.


.. _start:

=============
Documentation
=============

Job queues for TYPO3 CMS. Implements concrete queue for the database workqueue. Requires the exension **jobqueue** to be installed.


Configuration
-------------

This extension requires the `jobqueue <https://typo3.org/extensions/repository/view/jobqueue/>`_ extension.

In order to use this queue you should set the *defaultQueue* to ``R3H6\JobqueueDatabase\Queue\DatabaseQueue`` in the *jobqueue* extension settings.


Contributing
------------

Bug reports and pull request are welcome through `GitHub <https://github.com/r3h6/TYPO3.EXT.jobqueue_database/>`_.