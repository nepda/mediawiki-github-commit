mediawiki-github-commit
=======================

lists defined count of commit messages of an given repo hosted on github.com

Linux installation
==================
 
`// cd /path/to/your/wiki/`

`cd extensions`

`git clone https://github.com/nepda/mediawiki-github-commit.git`

 
open LocalSettings.php and add this line somewhere (at the end)

`require_once("$IP/extensions/mediawiki-github-commit/github-commit.php");`

 
Now you can fetch github-commit messages:

`<githubcommit user="nepda" repo="mediawiki-github-commit" offset="0" count="10">`
