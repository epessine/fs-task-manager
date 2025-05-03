# Requirements
1. Docker

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

4. Install npm dependencies and run migrations
```bash
./vendor/bin/sail npm install
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate --seed
```

5. Open http://localhost/ and login as
```
test1@example.com or test2@example.com
password
```

## Real Time Updates

To activate real-time updates on the app, run
```bash
./vendor/bin/sail artisan postgres:listen
```
This will start up the Reverb server, the queue worker and the listener service for Postgres events.

## Running Tests
```
./vendor/bin/sail artisan test
```
