# Talav monorepo

Talav project is an attempt to create a set of bundles for micro SaaS applications.
It's an open source project that can be reused by any other SaaS.
It follows monorepo approach. We will be using the same Jira to manage all tasks for the project

 - Main monorepo: https://github.com/Talav/Talav
 - All commits and PRs should be done in the main monorepo
 - Majority of code is heavily tested
 - Code has a lot of settings, some of the are not necessary for this project
 - It also allows not to have FosUserBundle and Sonata dependencies. First one is not really supported anymore and second one is too big and unstable
 
The similar development process:
 
 - The only change in the process is that all PRs should be done in the github   