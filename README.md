# Soupmix


[![Latest Stable Version](https://poser.pugx.org/soupmix/base/v/stable)](https://packagist.org/packages/soupmix/base) [![Total Downloads](https://poser.pugx.org/soupmix/base/downloads)](https://packagist.org/packages/soupmix/base) [![Latest Unstable Version](https://poser.pugx.org/soupmix/base/v/unstable)](https://packagist.org/packages/soupmix/base) [![License](https://poser.pugx.org/soupmix/base/license)](https://packagist.org/packages/soupmix/base)


Simple database abstraction layer adapters collection to handle CRUD operations written in PHP. This library does not provide any ORM or ODM. 

## Adapters

* **MongoDB: Exists**
* **Elasticsearch: Exists**
* Couchbase: Planned
* **Doctrine DBAL: Exists**
** **MySQL**
** **PostgreSQL**
** **Microsoft SQL**
** **Oracle**
** **SQLite**





## Installation
**This library contains only interface and abstract classes**. To use with a database you have to install the package written for that database. i.e: soupmix/mongodb for MongoDB;

It's recommended that you use [Composer](https://getcomposer.org/) to install Soupmix.

```bash
$ composer require soupmix/base "~0.6"
```

This will install Soupmix and all required dependencies. Soupmix requires PHP 5.6.0 or newer.


## Documentation

[API Documentation](https://github.com/soupmix/base/blob/master/docs/API_Documentation.md): See details about the db adapters functions:

## Usage
```
// Connect to MongoDB Service
$adapter_config = [];
$adapter_config['db_name'] ='db_name';
$adapter_config['connection_string']="mongodb://127.0.0.1";
$adapter_config['options'] =[];
$m=new Soupmix\Adapters\MongoDB($adapter_config);

// Connect to Elasticsearch Service
$adapter_config             = [];
$adapter_config['db_name']  = 'indexname';
$adapter_config['hosts']    = ["127.0.0.1:9200"];
$adapter_config['options']  = [];

$e=new Soupmix\Adapters\ElasticSearch($adapter_config);

$docs = [];
$docs[] = [
	"full_name" => "John Doe",
      "age" => 33,
      "email"	=> "johndoe@domain.com",
      "siblings"=> [
        "male"=> [
          "count"=> 1,
          "names"=> ["Jack"]
        ],
        "female"=> [
          "count" => 1,
          "names" =>["Jane"]
		]      
      ]
];
$docs[] = [
	"full_name" => "Jack Doe",
      "age" => 38,
      "email"	=> "jackdoe@domain.com",
      "siblings"=> [
        "male"=> [
          "count"=> 1,
          "names"=> ["John"]
        ],
        "female"=> [
          "count" => 1,
          "names" =>["Jane"]
		]      
      ]
];

$docs[] = [
	"full_name" => "Jane Doe",
      "age" => 29,
      "email"	=> "janedoe@domain.com",
      "siblings"=> [
        "male"=> [
          "count"=> 2,
          "names"=> ["Jack","John"]
        ],
        "female"=> [
          "count" => 0,
          "names" =>[]
		]      
      ]
];

foreach($docs as $doc){
	// insert user into database
	$mongo_user_id = $m->insert("users",$doc);
	$es_user_id = $e->insert("users",$doc);

}
// get user data using id
$es_user_data = $e->get('users', "AVPHZO1DY8UxeHDGBhPT");


$filter = ['age_gte'=>0];
// update users' data that has criteria encoded in $filter
$set = ['is_active'=>1,'is_deleted'=>0];

$e->update("users",$)

$filter = ["siblings.male.count__gte"=>2];

//delete users that has criteria encoded in $filter
$e->delete('users', $filter);



// user's age lower_than_and_equal to 34 or greater_than_and_equal 36 but not 38
$filter=[[['age__lte'=>34],['age__gte'=>36]],"age__not"=>38];

//find users that has criteria encoded in $filter
$docs = $e->find("users", $filter);


```







## Contribute
* Open issue if found bugs or sent pull request.
* Feel free to ask if you have any questions.
