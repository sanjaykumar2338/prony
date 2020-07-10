# Development Process
Development process uses a light version of Kanban with additional required step for code review.

### Prerequisites 
 - Make sure your work email is added to the jira and bitbucket, if not communicate the blocker
 - Make sure you can open main Kanban dashboard: https://etepia.atlassian.net/secure/RapidBoard.jspa?rapidView=2&projectKey=PRON. Make sure you can chceckout main repo
 - Make sure you can checkout main repo: https://bitbucket.org/wizardz/prony/src/master/

### Process
 - Go to https://etepia.atlassian.net/secure/RapidBoard.jspa?rapidView=2&projectKey=PRON&selectedIssue=PRON-1&quickFilter=3
 - Make sure "Only my issues" filter is ON. Do not take tickets assigned to other engineers
 - Take the top ticket from the TO DO column and move ti to IN PROGRESS
 - Add a link to the ticket (eg https://etepia.atlassian.net/browse/PRON-1) to the Upwork tracker, we should not have any timestamps without a ticket link
 - Familiarise yourself with the ticket, make sure to check "Original Estimate" value. In a long run estimation should be relatively close to the actual value
 - Make sure you have last version of the code and database. Before working on something run
```
git checkout master && git pull --rebase && composer install && bin/reset.sh
``` 
 - Create new branch for the ticket, ti should start from the ticket key, eg "PRON-1-add-default-values"
```
git checkout -b PRON-1-add-default-values
```
 - Complete the problem including unit and functional tests if applicable. 
 - Use PHP 7.4 features with correct type hinting
 - Use existing code to understand approaches we use, keep things consistent. In a lot of places it's just a matter of personal preferences and does not mean current approach is better
 - Fix all code style issues. Ping me if you've used a better schema in the past and we can improve here
```
composer fix-cs
```
 - Run all unit tests
```
./vendor/bin/simple-phpunit
```
 - Commit you code. Every timeframe longer than 2 hours should have at least one commit. But do not commit every 10 min, it creates unnecessary noise
 - Push your code to the server
```
git push -u origin PRON-1-add-default-values
```
 - Create PR, include ticket number in the PR. All functionality should be tested before creating the pull request
 - Move ticket to the "WAITING FOR CODE REVIEW" state, do not change ticket owner. At the end we want to run stats for engineers.
 - Add time spent to the ticket (click ... in the top right corner and choose Log Work)
 - Ideally it should only be 1-2 tickets in the "IN PROGRESS" state unless there is a serious blocker
 - After PR is approved merge the PR and move ticket to the "DONE" state
 -- Usually PRs have few comments and do not require long back and fourth
 -- For all approved PRs please address all additional comments and merge
 -- All "nit" comments are optional, use your judgement to define we need to implement them
 
### Upwork time tracking
 - The ideal tracking looks like 40-80 min working blocks with short breaks after each. 
 - Please minimize the amount for standalone 10 min blocks with non tracked time before and after. It takes time to get into development flow thus a block like that indicates a gap in the productivity