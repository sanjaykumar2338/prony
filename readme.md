Installation

* Update you `/etc/hosts` file, include all test domains
```
# main subdomain
127.0.0.1 prony.local

# few test worspaces
127.0.0.1 w1.prony.local
127.0.0.1 w2.prony.local
127.0.0.1 w3.prony.local
127.0.0.1 w4.prony.local
127.0.0.1 w5.prony.local

# few custom domains
127.0.0.1 w6.local
127.0.0.1 w7.local
127.0.0.1 w8.local
127.0.0.1 w9.local

# complicates case 
127.0.0.1 workspace.local
127.0.0.1 w10.workspace.local

```

* Install PHP 7.4+. Make sure it's correct
```
php -v
```

* Install composer, see https://getcomposer.org/doc/00-intro.md#locally
```
brew install composer
```

* Install yarn, see https://classic.yarnpkg.com/en/docs/install#mac-stable
```
brew install yarn
```

* Install Docker

* Clone project
```
cd <one level up from project path>
git clone git@bitbucket.org:wizardz/prony.git
```

* Install project
```
composer install
```

* Check system requirements
```
./vendor/bin/requirements-checker
```


Development

* Run Docker
```
cd <project path>
docker-compose up -d
```

* Reset database 
```
bin/reset.sh
```

* Run Symfony server
```
bin/console server:run
```

Fixtures users

| Login | Email | Password | Description |
| ----- | ----- | -------- | ----------- |
| admin | admin@test.com | admin | Super admin, should have Prony admin access
| tester1 | tester1@test.com | tester1 | Workspace 1 Owner
| tester2 | tester2@test.com | tester2 | Workspace 2 Owner
| tester3 | tester3@test.com | tester3 | Workspace 3 Owner
| tester4 | tester4@test.com | tester4 | Workspace 4 Owner
| tester5 | tester5@test.com | tester5 | Workspace 5 Owner
| tester6 | tester6@test.com | tester6 | Workspace 6 Owner
| tester7 | tester7@test.com | tester7 | Workspace 7 Owner
| tester8 | tester8@test.com | tester8 | Workspace 8 Owner
| tester9 | tester9@test.com | tester9 | Workspace 9 Owner
| tester10 | tester10@test.com | tester10 | Workspace 10 Owner

Domains

| Domain | Description |
| ------ | ----------- |
| prony.local | Main website for Prony
| w1.prony.local | Workspace 1, uses subdomain
| w2.prony.local | Workspace 2, uses subdomain
| w3.prony.local | Workspace 3, uses subdomain
| w4.prony.local | Workspace 4, uses subdomain
| w5.prony.local | Workspace 5, uses subdomain
| w6.local | Workspace 6, uses custom domain
| w7.local | Workspace 7, uses custom domain
| w8.local | Workspace 8, uses custom domain
| w9.local | Workspace 9, uses custom domain
| w10.workspace.local | Workspace 10, uses custom third level domain

Symfony web server uses 8000 port, so URL will be http://prony.local:8000, http://w1.prony.local:8000

Documentation

 - [Development process](./doc/process.md)
 - [Testing](./doc/testing.md)
 - [Initial specification](https://docs.google.com/document/d/17kZKc9tBAe4KkHaI7QPLybcOqWUxNB3W-bRUoiNZHT8/edit#heading=h.krtdshor2fhg)