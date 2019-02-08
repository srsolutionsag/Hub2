# Changelog

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
