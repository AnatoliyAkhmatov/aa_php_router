# wordpress / native php router

check api tokens in headers
```php
$tokens = [
  'XCrb1TDueG', // vilatiy
  
  ...
];
```

initialize a router

```php
$router = new Router;
```

create a routers with regular expression and without get arguments
```php
$router->get('/api$/', function($options) { ... })
$router->post('/api$/', function($options) { ... })
$router->put('/api$/', function($options) { ... })
$router->delete('/api$/', function($options) { ... })
```

get variables from $_POST / $_GET / $_PUT in $options

```php
// /api/users/create
$router->post('/api\/users\/create$/', function($options) {
  $options->name;
  
  ...
})
```

```php
// /api/users?limit=10?offset=2
$router->post('/api\/users$/', function($options) {
  $options->limit;
  $options->offset;
  
  ...
})
```

send json
```
// native
echo json_encode([...]);

// for wordpress
wp_send_json([...]);
```
