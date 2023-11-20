## Requirements

You needs:
- Docker
- Make

## Installation with Docker

From the plugin root directory, run the following command:

```bash
$ make start
```

Then enter container through the following command:

```bash
$ make connect
```

From the `app` container, run the following command:

```bash
$ make init
```

## Usage

### Running code analyse and tests

From the `app` container, several actions are available:

#### Tests

- PhpSpec:

    ```bash
    $ make phpspec
    ```
  
- PhpUnit:

    ```bash
    $ make phpunit
    ```
  
- Behat (tests without Javascript support):

    ```bash
    $ make beaht-no-js
    ```
  
- Behat (tests with Javascript support):

    ```bash
    $ make beaht-js
    ```

#### Code analyses
   
- Lint (yaml/twig):

    ```bash
    $ make lint
    ```

- PhpStan:

    ```bash
    $ make phpstan
    ```

- Code style (easy-coding-standard, twigcs, php-cs-fixer):

    ```bash
    $ make code-style
    ```
    Some automatic corrections may then be made through `make code-style-fix`.


- Composer checks:
  - Validate

      ```bash
      $ make validate
      ```

  - Normalize

      ```bash
      $ make normalize
      ```
    Some automatic corrections may then be made through `make normalize-fix`.

All of these tasks can be played running:

```bash
$ make static
```

### Pre-commit hook

GrumPHP is executed by the Git pre-commit hook, but you can launch it manualy with :

```bash
$ make grumphp
```

This makes some static code analyses (see configuration [grumphp.yml](grumphp.yml)). 

### Opening Sylius with your plugin

- Using `test` environment: http://localhost/
- Using `dev` environment: http://localhost:81/

### Miscellaneous

- Using PhpMyAdmin: http://localhost:8082/
  - login: root
  - password: mysql
