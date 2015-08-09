# Abstract superclass for domain models.

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

### Using setData method
```php
<?php

$user = new UserModel();

$data = array('username' => 'JohnDoe');
$user->setData($data);

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
