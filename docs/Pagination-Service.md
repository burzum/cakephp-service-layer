# Pagination Service

The pagination service is basically like the pagination component, just that it is not coupled to the controller object. All it needs is the request object.

As you know services can use other services to compose business logic.

Service:

```php
class ArticlesService
{
    use ServiceAwareTrait;

    protected $request;

    public function __construct(ServerRequest $request)
    {
        $this->request = $request;
        $this->loadService('Pagination', [$request]);
        $this->loadModel('Articles');
    }

    public function listing()
    {
        // Assuming you're using the official Authentication plugin!
        // If not using it pass the user id from the controller to the listing() method
        $userId = $this->request
          ->getAttribute('identity')
          ->getIdentifier();

        $query = $article->find()
            ->limit(10)
            ->where([
                'Articles.user_id' => $userId
            ])
            ->orderDesc('created');

        return $this->Pagination->paginate($query);
    }
}
```

Controller:

```php
class ArticlesController extends AppController
{
    // Should be declared in your AppController it's just shown here
    // for demonstration purpose
    use ServiceAwareTrait;

    public function initialize()
    {
        parent::initialize();
        $this->loadService('Articles');
    }

    public function index()
    {
        $this->set('articles', $this->Articles->listing());
    }
}
```

The controller will become really skinny and all the business logic is encapsulated inside the ArticlesService.
