Resort question categories Moodle plugin
========================================

Requirements
------------
- Moodle 4.0 (build 2022041900) or later.

Installation
------------
Copy the renumbercategory folder into your Moodle /question/bank directory and visit your Admin Notification page to complete the installation.

Usage
-----
Question bank navigation node will be extended with "Number categories" item. Select category you want to renumber. All subcategories will
be hierarchicaly renumbered depending of current categories order. All previous numbering will be removed. If you want to renumber subcategory, 
you may specify number prefix. This plugin may be useful to manage large question bank together with qbank_resortcategory 
plugin.

Author
------
- Vadim Dvorovenko (Vadimon@mail.ru)

Links
-----
- Updates: https://moodle.org/plugins/view.php?plugin=qbank_renumbercategory
- Latest code: https://github.com/vadimonus/moodle-qbank_renumbercategory

Changes
-------
- Release 0.9 (build 2016041601):
    - Initial release.
- Release 1.0 (build 2016051000):
    - Extra capability check.
- Release 1.1 (build 2020061300):
    - Privacy API support.
    - Question bank tabs.
- Release 2.0 (build 2025020400)
    - Renamed from local_renumberquestioncategory to qbank_renumbercategory.
    - Refactored for Moodle 4 question bank changes.
    - Renaming category fires event and is logged.