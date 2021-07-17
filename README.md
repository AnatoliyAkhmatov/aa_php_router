# simple php router / also wordpress router

initialize a router

```php
$router = new Router;
```

create a routers with regular expression and without get arguments
```php
$router->get('/api', function($options) { ... })
$router->post('/api', function($options) { ... })
$router->put('/api', function($options) { ... })
$router->delete('/api', function($options) { ... })
```

get variables from $_POST / $_GET / $_PUT in $options

```php
/api/posts/mypost
$router->post('/api/posts/{slug}', function($options) {
  $options->slug;
  
  ...
  
  exit;
})
```

```php
// /api/users?limit=10?offset=2
$router->post('/api/users', function($options) {
  $options->limit;
  $options->offset;
  
  ...
  
  exit;
})
```

send json
```
// native
echo json_encode([...]);

// for wordpress
wp_send_json([...]);
```
