# Testing

### How to test local emails
Docker compose requires image of MailHog and makes it available at http://localhost:8025/
UI shows all images sent by the system

### How to run phpunit suite
```
./vendor/bin/simple-phpunit
```
It takes some time to execute the full suite, filter by name
```
./vendor/bin/simple-phpunit --filter WorkspaceTest
```