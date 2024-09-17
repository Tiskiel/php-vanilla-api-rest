# Welcome to my API REST on PHP vanilla

I opted for a clean architecture with repositories, services, and controllers.
I have limited the use of external libraries, challenging myself to build as much as possible in vanilla PHP.
For testing, I used the Pest framework, which is based on PHPUnit.

## How to use it

Don't forget to set up your `.env` file based on the `.env.example.`

Once all the steps below are completed, you need to make a `POST` request to `https://localhost:8000/login` to obtain a token.
After that, you need to use the token, prefixed with `Bearer ` , and set the Authorization header.

### 1. Clone the repository

```bash
https://github.com/Tiskiel/test-eonix.git
```

### 2. Install dependencies

```bash
composer install
```

### 3. Run the migrations

```bash
composer users:migrate
```

### 4. Run the seeders

```bash
composer users:seed
```

### 5. Start the server

```bash
composer serve
```

### To run the tests with Pest

```bash
composer test
```
