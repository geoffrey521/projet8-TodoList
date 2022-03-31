## How to contribute.

* At first, clone the repository

1. **Git workflow**

* Never work directly on "main" or "develop".

* Create branch locally from "develop".

* Test your code with PHPunit before pushing

* When you finished your issue, push branch in repository.

* Create a new pull request from your branch to "develop" and comment "close #1" (replace 1 by the number of the issue).

Issue will be automatically closed after merging.

* Checkout if the branch is able to merge and symfony insight pass tests.

* If symfony insight throw some errors, refer to it and fix them before merging.

* If all checks passed, you'll be able to merge.

2. **Branch names**

Your branch name begin by the type of the issue (feature, bugfix, hotfix) followed by a "/TD-", the number of the issue and the name of the issue.
Each word have to be separate by a "-".

````
exemple: feature/TD-4-add-access-control
````

3. **Commits messages**

* Like your branch, commit message start by the type of the issue, follow by "#" and the number of the issue, followed by ":" and your message.

````
exemple: "Bugfix #2 : Fix user login" 
````
