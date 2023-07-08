### 1. Clone project from git

```bash
git clone git@github.com:DmitriyLishikov/sweet-cinema.git
```

### 2. Install composer dependencies

```bash
 composer install
```

### 3. Install JS dependencies

```bash
npm install

npm run build
```

### 4. Set up enviroment variables

1. Copy `.env.example` file to `.env`.
2. Fill `.env` file keys with your own values

### 5. Migrate

```bash
php artisan migrate
```

### 6. Storage link

```bash
php artisan storage:link

```

### 7. Start server

```bash
php artisan serve or valet 

```
