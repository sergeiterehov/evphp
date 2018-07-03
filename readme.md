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