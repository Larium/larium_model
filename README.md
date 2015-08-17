# Abstract superclass for domain models.

## Installation
You can install this library using [Composer](http://getcomposer.org)
### Command line
In root directory of your project run through a console:
```bash
$ composer require "larium/model":"~1.0"
```
### Composer.json
Include require line in your ```composer.json``` file
```json
{
	require: {
    	"larium/model": "~1.0"
    }
}
```
and run from console in the root directory of your project:
```bash
$ composer update
```

After this you must require autoload file from composer.
```php
<?php
require_once 'vendor/autoload.php';
```

## Usage

```php
<?php
# UserModel.php

class UserModel extends Larium\AbstractModel
{
    protected $username;

    protected $email;
}
```

### Using default constructor.

```php
$user = new UserModel();

$user->setUsername('JohnDoe');

echo $user->getUserName(); # echoes JohnDoe.
```

### Using create method

```php
<?php

$data = array('username' => 'johnDoe');
$user = UserModel::create($data);

echo $user->getUserName(); # echoes JohnDoe.
```

### Using assignData method
```php
<?php

$user = new UserModel();

$data = array('username' => 'JohnDoe');
$user->assignData($data);

echo $user->getUsername(); # echoes JohnDoe
```
### Using create method with constructor arguments
```php
<?php

# CommentModel.php

class CommentModel extends AbstractModel
{
    protected $user;

    protected $content;

    public function __construct(UserModel $user)
    {
        $this->user = $user;    
    }
}
```

```php
<?php
$user = new UserModel();
$date = array('content' => 'Lorem Ipsum');
$comment = CommentModel::create($data, array($user));

$comment->getUser(); # return UserModel instance.
```
