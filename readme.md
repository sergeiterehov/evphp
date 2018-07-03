# Пример модуля

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

использование

```php
use evphp\IO;

$name = new IO;

$hello = new Hello;

$hello['name'] = $name;
$message = $hello['message'];

$name->set('Alex');
echo $message->get(), "\n";
```

# Изолированный тест

```bash
docker build -t evphp_test .
docker run --rm evphp_test
```

# Отладочное тестирование

```bash
docker-compose run --rm test composet install
```

запуск

```bash
docker-compose up
```