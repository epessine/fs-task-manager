# Run instructions

1. Copy env file
```bash
cp .env.example .env
```

2. Install composer dependencies
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs
```

3. Start the containers
```bash
./vendor/bin/sail up
```

4. Install npm dependencies
```bash
./vendor/bin/sail npm install
```

5. Run migrations and seeder
```bash
./vendor/bin/sail artisan migrate --seed
```

6. Open http://localhost/ and login as
```
test1@example.com or test2@example.com
password
```
