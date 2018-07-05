# Module example

```php
use evphp\Module;

/**
 * Module Hello
 *
 * @property $name
 * @property $message
 */
class Hello extends Module
{
    public function schema()
    {
        $this->in('name');
        $this->out('message');

        $this->always('name')->do(function () {
            $this->message = "Hello, {$this->name}!";
        });
    }
}
```

using

```php
use evphp\IO;

$name = new IO;

$hello = new Hello;

$hello['name'] = $name;
$message = $hello['message'];

$name->set('Alex');
echo $message->get(), "\n";
```

# Isolated test

```bash
docker build -t evphp_test .
docker run --rm evphp_test
```

# Debug testing

```bash
docker-compose run --rm test composet install
```

run test

```bash
docker-compose up
```