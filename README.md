# Interest Accounts

Manage user interest accounts.

## Development

Docker is used to provide a development environment, which is available during
the development lifecycle via the following `make` commands:

```console
dev:~$ make help

  check             Run the library's code quality checks (test, analysis)
  help              List supported commands
  shell             Log in to the development container
  test              Run the library's tests
```

### Code Quality

Strict code quality requirements are enforced for each commit,
[`vimeo/psalm`][psalm] provides Static Analysis and code analysis is provided by
[`nunomaduro/phpinsights`][php-insights] -- these tools are configured in
[`psalm.xml`](psalm.xml) and [`insights.php`](insights.php) respectively.

### CI

A GitHub Workflow ([`test`][workflows-test]) automatically tests the library
against all PHP versions included in `strategy.matrix.php`.

### Hooks

A pre-commit Git Hook is included for ensuring compliance with code
requirements on commit, enable the Git Hook by running the following command:

```console
dev:~$ git config core.hooksPath .github/hooks
```


[workflows-test]: .github/workflows/test.yml
[psalm]: https://psalm.dev
[php-insights]: https://phpinsights.com
