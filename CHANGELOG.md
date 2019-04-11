# Changelog

## [x]
- Fix reset offset

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
