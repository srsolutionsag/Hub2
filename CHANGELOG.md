# Changelog

## Version 4.6.0
- [FIX] ByEmail array access in some cases
- [FEATURE] calendar settings in courses
 
## Version 4.5.0
- [FIX] pretty print array objects in additional data
- [FIX] prepared restoring permissions
- [FIX] titles of dependent categories
- [FIX] disabled restoring of parent
- [FIX] find dependent category
- [FIX] show name of mapping strategy in log
- [FIX]issue in status table with empty ilias_id
- [FIX] type of rediooptions
- [FIX] added message to logs table
- [FIX] allow NullDTo as well
- [FIX] ignore existing accounts > 13
- [FIX] delete old logs parameter
- [FIX] call to undefined method ilHub2OriginsGUI::cancel().
- [FIX] undefined property ActiveRecord::\$parent_id due to wrong method call.
- [FIX] wrong default value type
- [FIX] only generate import ids up to 50 characters.
- [FIX] match ILIAS 8 ilContainer::addTranslation() type-hints.
- [FEATURE] general code improvements
- [FEATURE] reworked GUI classes
- [FEATURE] New Log-Table and Purge Mechanism to avoid huge data

## Version 4.4.0
- [FEATURE] allow more options on csv imports
- [FIX] Error while saving Origin due to already processed Uploads
- [FIX] missing default value for course membership origins

## [4.3.3]
- [FIX] memory leak in metadata handling
- [FIX] dessign hub objects from ILIAS-ID if the ILAIS Object has been deleted
- [FIX] show error message in logs table again

## [4.3.2]
- [FIX] memory leaks on status and origins overview

## [4.3.1]
- [FIX] renamed stakeholder in DB
- [FIX] improved performance in logs table

## [4.3.0]
- [FEATURE] added new MappingStack
- [FIX] error while deleting origin
- [FIX] error while creating origin
- [FEATURE] finalized implementation of API source

## [4.2.3]
- [FIX] filedrop settings array access
- [FIX] proper check for origin implementation

## [4.2.2]
- [FIX] Shortlinks did not work in some cases
- [FIX] Limited A cess Time for User Accounts
- [FIX] Reference to a deleted class in UserDTO
- [FIX] Manual FileDrop for files now working

## [4.2.1]
- [FIX] fixed storage of MetaData in Database

## [4.2.0]
- [FEATURE] added possibility to exchange string sanitizer in Origin for JSON based implementations
- [FIX] Fixed Issue with truncated timestamps with newer MySQL versions

## [4.1.1]
- [FIX] handle empty strings in json parser

## [4.1.0]
- [FEATURE] added specific connection type "API"

## [4.0.7]
- [FIX] Course and Group Membership constants

## [4.0.6]
- [FIX] general errors during cron in CLI context

## [4.0.5]
- [FIX] Incompatible Syntax in PHP 7.4
- [FIX] make Plugin installable using cli
- [FIX] more PHP 7.4 syntax incompatibilities

## [4.0.4]
- [FIX] Several PHP8 and ILIAS 8 incompatibilities 

## [4.0.3]
- [FIX] bad method call after refactoring
- [FIX] error in cli context for cron notifier

## [4.0.2]
- [FIX] catch errors writing to session
- [FIX] update RegistrationType in Groups

## [4.0.1]
- [FIX] Fixed Group-Type, Registration Mode and Start-/End on Create

## [4.0.0]
- [RELEASE] Support for ILIAS 8 (only)

## [3.5.1]
- [FIX] Fixed Parent Ref ID for Groups (Restoring Objects in Trash)
- [FIX] Fixed Start / End Date for Groups

## [3.5.0]
- [IMPROVEMENT] Several Refactorings before first Version for ILIAS 8
- [FIX] Removed non working Icon implementation

## [3.4.1]
- [FIX] Fixed Sorting in Origins Table

## [3.4.0]
- [FEATURE] Support for Learning Progress Settings in Courses 

## [3.3.1]
- [FIX] Empty ILIAS-Links in LogsTable 

## [3.3.0]
- Removed DICTrait, HUB2Trait and external libraries 

## [3.2.1]
- Fixed Shortlinks to Courses if not logged in
- Download FileDrops with Origin Name
- Check File-Content of File-Drops before storing

## [3.2.0]
- Improvement to reassign Didactic Templates recursively
- Fix in FromHubToHub2 MappingStrategy: Use only id Hub (1) table is avaliable
- Upload Files to FileDrop manually
- JsonParser

## [3.1.0]
- Support for OpenID Connect Accounts
- Support for Second E-Mails in User Accounts
- News-Settings for Courses
- File-Drop as default Connection Type
- CSV-Base-origins with Generators for faster performance and better memory management
- Recursive Didactic Templates

## [3.0.2]
- Fixed critical but which caused course members to stay in courses in status OUTDATED

## [3.0.0]
- ILIAS 6/7 support
- Remove ILIAS 5.3 support

## [2.4.0]
- Improved Logging: log on update and only if data changed, added status to logs
- Fix: User set passwd
- OrgUnits are now Mapping Strategy Aware
- Improvement Log Table: allow usage of wildcard (%) for external i (%) for external id
- Fix Error notification was sent without errors occuring
- Fix course subscription types
- Competence management origin type
- Fix install log
- New default path to origins '/var/www/ilias/Customizing/global/origins/'
- Fix language keys with line break
- Fix read fallback parent ref id read
- Write rbac log when creating object
- Fix: mapping byTitle for Org Units
- Feature: User DTO with language
- Origins can now set course start and end date
- Assign ILIAS default role to user per default
- Mapping strategy by ext id
- Fix missing move session implementation
- Fix insert on update, if insert failed on create
- Feature: user origin config to keep login case (instead of all to lower case)
- Fix set new metadata fields

## [2.3.0]
- Support ILIAS 5.4
- Optimize `handleSort` in org unit
- Pass also the dto object to `handleDelete`
- Fix missing `srag\Plugins\Hub2\Origin\AbstractOriginImplementation` import in origin class template
- Refoctoring/Performance logs: Use native MySQL AutoIncrement and reset it after remove logs
- `wakeUpValue`/`sleepValue` for `DataTransferObject`
- Fix reset offset
- Remove `DICStatic::clearCache();` because replaced by pass by reference in DICTrait
- Config nove behavior for org units
- Config delete behavior for org units and org unit memberships

## [2.2.5]
- Optimized log table

## [2.2.4]
- Fix log table ext id filter
- Fix status table export contains Actions columns
- Logs table can now exported too
- Fix course membership readd on update

## [2.2.3]
- Fix connection getPath validation
- Fix getAppointmentColor is NULL
- Logs invalid origin implementation namespace\\class
- Fix log table origin type filter
- Store new possible ilias id on exception (`handleCreate`|`handleUpdate`|`handleDelete` returns now nothing anymore, but it will store the ilias object on `$this->current_ilias_object`, so it can read the ilias id also on exception)

## [2.2.2]
- Fix ILIAS file selector

## [2.2.0]
- Add ILIAS file as a origin connection type
- Improves config getters

## [2.1.0]
- Some improvments and fixes

## [2.0.0]
- Ask for removing data
- AdHoc feature
- CustomView feature
- GlobalHook feature
- New logs will be logged in database, not in files anymore. Also cron job to automatic delete old logs with config days
- More meaningful GUI for force update synch: Buttons. Also implement this for a single origin synch
- New origin sync status model
- New FAILED status, all exceptions will set the object to this status
- MappingStrategy ByImportId
- Sort origins feature
- Clean up status modal DTO
- Exceptions are converted in logs
- Logs are send in email
- handleExceptions is now handleLog
- Removed notifications which replaced with logs
- Fix: Add correct namespace for new origins
- Fix: Not delete data on origin delete
- Fix: Recreate deleted users
- Fix: OrgUnit restore from trash
- Refactoring, using new libraries
- PHP version checker
- Some other fixes and improvements
