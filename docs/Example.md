# Simple Pagination Example

## Actual State

This is a fat controller, not using a table or service for business logic to implement the listing of jobs for the current logged in user.

```php
// App\Controller\JobsController.php
public function index()
{
    $query = $this->Jobs->find()
        ->where([
            'Jobs.user_id' => $this->Auth->user('id'),
            'Jobs.active' => true,
        ]);

    $this->set('jobs', $this->paginate($query));
}

// App\Shell\JobsShell.php
public function listJobs()
{
    $jobs = $this->Jobs->find()
        ->where([
            'Jobs.user_id' => $this->Auth->user('id'),
            'Jobs.active' => true,
        ])
        ->all();

     $this->printJobList($jobs);
}
```

Now lets refactor this to move the logic into the right places, the table and service:

## Refactoring

```php
// App\Controller\JobsController.php
public function index()
{
    $this->set('jobs', $this->Jobs->getListForUser(
        $this->Auth->user('id',
        $this->request
    ));
}

// App\Model\Table\JobsTable.php
public function findActive(Query $query)
{
    return $query
        ->where([
            $this->aliasField('active') => true
        ]);
}

// App\Service\JobsService.php
public function getListForUser($userId, $queryParams)
{
    $query = $this->Jobs->find('active')
        ->where([
            'Jobs.user_id' => $userId
        ]);

    return $this->paginate($query, $queryParams);
}

// App\Shell\JobsShell
public function listJobs()
{
    $jobs = $this->Jobs->getListForUser(
        $this->getParam('user',
        $this->getOptions()
    ));

    $this->printJobList($jobs);
}
```

## This looks like a lot more code? Why is it better?

- The controller doesn't need to know anymore anything about the used DB backend it doesn't access the DB directly through the table object anymore. If the underlying implementation changes, your controller doesn't need to be changed.
- The controller doesn't implement anymore logic
- No more duplicate code between controller and shell
- The logic of finding only active records was moved into the table because it's a concern of the DB object and it's query
- The actual logic of finding the jobs for a specific user and paginating it was moved into the service
- Now you can use the same code from a shell, controller or whatever else place you want
- Much better to test, especially controllers that contain a lot logic are not very nice to test

All of the above makes the application easier to test and extend and by this maintain. You always want to aim for easy and low maintenance to keep the total cost of ownership of your product low.
