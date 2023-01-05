# LogReader

KISS

The LogCount entity has only a few simple fields and getter/setter methods, making it easy to understand and use.
The LogCountRepository has only a few methods, each with a clear and concise purpose.
The LogController has only a single action method that handles the /logs/count endpoint, making it easy to understand and maintain.
The ParseLogCommand has a single execute() method that performs the parsing and saving of log data, keeping the code simple and focused.

DRY

The ServiceStatusRepository uses the Doctrine query builder to construct queries, avoiding the need to write raw SQL and reducing duplication of query logic.
The LogController and ParseLogCommand both use the ServiceStatusRepository to perform database operations, avoiding the need to duplicate repository logic in multiple places.
<h3>Rest Api Endpoint</h3>

<p>http://localhost/logs/count</p>


<h3>For The Command to Import</h3>

<p>php bin/console log:create</p>

The file is at the moment in public directory name logs.txt

<h3>Created The test case</h3>

Create one test case which checks if the rest api endpoint is valid and return 200 response
