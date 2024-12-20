# SmartFilter

SmartFilter is a Laravel package designed to provide robust and flexible filtering capabilities for APIs. It allows developers to apply filters, sorting, and pagination to data retrieval seamlessly while returning structured JSON responses.

---

## Installation

1. **Require the package via Composer:**
   ```bash
   composer require pedrokeilerbatistarojo/smartfilter
   ```

2. **Publish the configuration (optional):**
   ```bash
   php artisan vendor:publish --provider="Pedrokeilerbatistarojo\Smartfilter\SmartfilterServiceProvider"
   ```

3. **Usage:** Include the package in your controller or service to start applying filters.

---

## Features

- Apply filters with various operators (e.g., `like`, `=`, etc.).
- Select specific columns to retrieve.
- Include relationships for eager loading.
- Sort results by any field.
- Paginate results with customizable parameters.
- Structured JSON responses with metadata.

---

## Usage Example

### Sample Request

```http
GET /api/users?filters[0][0]=name&filters[0][1]=like&filters[0][2]=Owner&columns[]=id&columns[]=name&columns[]=email&includes[]=role&sortField=created_at&sortType=asc&itemsPerPage=8&currentPage=1
```

### Query Params
```
filters:[["name", "like", "Owner", "and"],["email", "like","owner@example.com", "and"],["name", "like", "Owner", "and", "role"]]
columns:["id", "name", "email"]
includes:["role"]
sortField:created_at
sortType:asc
itemsPerPage:8
currentPage:1
```

### Controller Example

```php
use Pedrokeilerbatistarojo\Smartfilter\Services\FilterService;
use Pedrokeilerbatistarojo\Smartfilter\Helpers\ResponseHelper;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;

class UserController extends Controller
{

    public function __construct(
        private readonly FilterService $filterService
    ){
    }

    /**
     * @throws Exception
     */
    public function __invoke(Request $request)
    {
       try {
          $response = $this->filterService->execute(User::class, $request->all());
          return ResponseHelper::sendResponse($response);
       }
       catch(Exception $ex){
          return ResponseHelper::sendError($ex->getMessage());
       } 
    }
}

```

### Expected JSON Response

```json
{
    "success": true,
    "message": "Search completed successfully",
    "errors": null,
    "payload": {
        "items": [
            {
                "id": 1,
                "name": "Owner",
                "email": "owner@example.com"
            },
            {
                "id": 2,
                "name": "Admin",
                "email": "admin@example.com"
            }
        ],
        "metadata": {
            "currentPage": 1,
            "lastPage": 5,
            "itemsPerPage": 8,
            "total": 40
        },
        "total": 40
    }
}
```

---

## Testing

1. **Run the test suite:**
   ```bash
   php artisan test
   ```

2. **Example Test:**

   ```php
   public function test_filter_with_filters(): void
   {
       $filters = [
           ['name', 'like', 'Owner', 'and'],
           ['email', 'like', 'owner@example.com', 'and'],
           ['name', 'like', 'Owner', 'and', 'role']
       ];

       $columns = ['id', 'name', 'email'];
       $includes = ['role'];

       $params = [
           'filters' => $filters,
           'columns' => $columns,
           'includes' => $includes,
           'sortField' => 'created_at',
           'sortType' => 'asc',
           'itemsPerPage' => 8,
           'currentPage' => 1
       ];

       $queryString = http_build_query($params);
       $endpoint = "/api/users?{$queryString}";

       $response = $this->get($endpoint);
       $response->assertStatus(200);
       $response->assertJsonStructure([
           'success',
           'message',
           'errors',
           'payload' => [
               'items' => [
                   '*' => [
                       'id',
                       'name',
                       'email'
                   ]
               ],
               'metadata' => [
                   'currentPage',
                   'lastPage',
                   'itemsPerPage',
                   'total'
               ],
               'total'
           ]
       ]);
   }
   ```

---

## Contributing

Feel free to fork this repository and submit pull requests. Ensure that all tests pass and maintain code quality standards.

---

## License

SmartFilter is open-source software licensed under the [MIT License](LICENSE).

