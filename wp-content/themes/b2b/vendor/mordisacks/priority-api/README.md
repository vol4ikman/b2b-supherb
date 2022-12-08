# priority-api
A PHP client for priority software API
based on this documentation  
https://prioritysoftware.github.io/restapi/  

### Instellation  
`composer require mordisacks/priority-api`

### Usage

#### Setup 
```php
$client = new PriorityClient($serviceRootUrl);
```

#### Authentication
```php
// Basic auth
$client->withBasicAuth('username', 'password');

// App auth
$client->withAppAuth('app_id', 'app_key');
```

```php
$query = new Builder();
$query->setClient($client);
```

#### Getting a collection
```php
$query->from('ORDER')
      ->select('A', 'B', 'C')
      ->filter('FOO', 'bar')
      ->orFilter('FOO', '!=', 'BAZ')
      ->expand('ITEMS_SUBFORM')
      ->top(3);
      
// Outputs the raw query: ORDER?$select=A,B,C&$filter=FOO eq 'bar' or FOO ne 'BAZ'&$expand=ITEMS_SUBFORM&$top=3
$query->toQuery(); 

// Returns a collection of ORDERS
$query->get(); 
```

#### Gettign a single Entity
```php
// Returns a single ORDER with the id of AA123456
$query->from('ORDERS')->find('AA123456');
```

#### Filter Group 
```php
$query->filter(function (Filter $filter) {
            $filter->filter('B', 'something');
            $filter->filter('C', 'something');
         });
```

#### Expand sub query
```php
$query->expand('ITEMS', function (Builder $q) {
      $q->select('FIELD1', 'FIELD2', 'FIELD3')
        ->filter('FIELD1', 'Y')
        ->filter(function (Filter $filter) {
            $filter->filter('FIELD2', 'Y')
                   ->orFilter('FIELD3', 'Y');
        });
  });
```
--------------

Inspired by Laravel Eloquant builder
