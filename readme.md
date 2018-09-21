HyperChan
=================

What is HyperChan?
-----------------
HyperChan is an opensource, simple and lightweight imageboard written in pure PHP and JQuery. It is still a work in progress and only a side project I created in my free time so any contributions are welcome and greatly appreciated.

Features
----------
* Written in pure PHP and JQuery to remain lightweight
* Built in API that can retrieve threads and reply's through JSON located at `http://your-site/api/retrieve.php/` (documentation coming soon)
* easily moldable and built with efficiency in mind
* could easily be adapted to work in a serverless environment (easy option for serverless will soon be added)
* secure image uploader

Requirements
-------------
* PHP5 or above (PHP7 is recommended)
* MySQL Server (Most recent release recommended)
* Webserver (Apache recommended)

Installation
------------
*installation can be easily completed with an installer script*
Instructions:
1. Fill out database address, username and password in `/api/config.php` using your text editor of choice.
2. using a browser navigate to `http://your-site/install.php`
3. Done!, the install script should automatically remove itself and you should be setup.

Contributing
-------------
All contributions are welcome and I will try to review and accept them as son as possible.
Currently, the areas that could use contributions are:
* The documentation
* CSS and stylesheets
* Clientside Javascript (I'm not a Javascript dev)
* Anything you see fit to work on

Todo
-----------
* add csrf tokens to prevent cross site scripting forgery attacks
* allow threads/replies to point to eachother with post id numbers
* Improve timestamping and add more timestamp locale's
* Improve the aesthetic of HyperChan
