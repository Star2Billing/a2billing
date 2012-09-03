##PHP Quick Profiler README
[http://particletree.com/features/php-quick-profiler/](http://particletree.com/features/php-quick-profiler/)

### On This Page

1. License
2. Introduction and Overview of Files
3. Getting the Example Working
4. Setting up the Database Class
5. Using Smarty instead of PHP echos

#### 1. License

Created by Ryan Campbell. Designed by Kevin Hale.

Copyright (c) 2009 Infinity Box Inc.

All code and designs are provided under a
Creative Commons Attribution 3.0 License.

http://creativecommons.org/licenses/by/3.0/us/

#### 2. Introduction and Overview of Files

PHP Quick Profiler (PQP) is a helper class that outputs to the screen information 
useful for debugging when the page has finished executing. If you haven't already, 
definitely read the article over at our blog introducing the profiler at:

http://particletree.com/features/php-quick-profiler/

This zip package contains a functional example project that utilizes the helper classes.
Here's a breakdown of the files:

- index.php : Contains example code utilizing PQP. Open this in your browser to see the demo.
- display.php : Contains the markup for PQP.
- display.tpl : A Smarty variation of the PQP markup.
- /css/ : The stylesheets used by PQP.
- /images/ : The images used by PQP.
- /classes/PhpQuickProfiler : The core class that compiles the data before outputting to the browser.
- /classes/Console.php : The class used to log items to the PQP display.
- /classes/MySqlDatabase : A sample database wrapper to show how database logging can be implemented with PQP.

#### 3. Getting the Example Working

For the most part, the example should work once you drop it in your root directory. 
There are a few settings you might want to check if it doesn't.

- In index.php, set the $config member variable to the path relative to your root.

- If PQP does not appear after navigating to index.php in your browser, locate the destructor 
of the PQPExample class (at the bottom). Rename the function from __destruct() to display(). 
Then, manually call the function display() just underneath the class after the call to init(). 
The reason this would happen is because the destructor is not firing on your server configuration.

- At this point, everything should work except for the database tab.

#### 4. Setting up the Database Class

*NOTE!*
This step requires knowledge of your PHP / Database interactions. 
There is, unfortunately, no copy/paste solution.

Logging database data is by far the hardest part of integrating PQP into your own project. 
It requires that you have some sort of database wrapper around your code. If you do, it 
should be easy to implement. To show you how it works, follow these steps with the 
sample database class we've provided with the code.

- Create a database named 'test' and run the following query on it.

CREATE TABLE `Posts` (
  `PostId` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`PostId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1

- In index.php, uncomment out the second include, which includes the database class.
- In index.php, uncomment out the function sampleDatabaseData().
- In the sampleDatabaseData(), supply your database host, username, password, and database name.

Given those steps, database logging will be enabled. If you would like to transition this 
to your own database class, open /classes/MySqlDatabase.php and note the following:

- $queryCount and $queries member variables declared on initialization
- When a query is run, the following is executed:

$start = $this->getTime();
$rs = mysql_query($sql, $this->conn);
$this->queryCount += 1;
$this->logQuery($sql, $start);

- Everything in /classes/MySqlDatabase.php under the section comment "Debugging"
must be available for the above snippet to work.

#### 5. Using Smarty instead of PHP echos

We love Smarty and hate using echos to spit out markup, but to make PQP work for as many people 
as possible we set the default to use the echo version. To show love to the Smarty users out 
there, we have included a display.tpl file for PQP. To make it work, you need to change
the following in /classes/PhpQuickProfiler.php:

- Add a require_once to your Smarty Library.
- In the constructor, declare an instance of Smarty: $this->smarty = new Smarty(...);
- Everywhere in in the code you see $this->output[... change it to a smarty assign. For example:

$this->output['logs'] = $logs;

... becomes ...

$this->smarty->assign('logs', $logs);

After doing it once, you'll see the pattern and can probably use a find/replace to do the rest quickly.

- Locate the display() function at the bottom. Remove the last 2 lines, and add:

$this->smarty->display('pathToDisplay.tpl');

And then you're all set after that! Have fun and happy debugging!