# Interest Accounts

Manage user interest accounts.

## Usage

```php
$userId = new UserId('88224979-406e-4e32-9458-55836e4e1f95');

OpensInterestAccounts::class->openInterestAccount($userId);

$account = OpensInterestAccounts::class->interestAccountByUserId($userId);

AwardsInterest::class->awardEarnedInterest($account);

$account->balance();
```

| Contract | Implementations |
| -------- | -------------- |
| [`AwardsInterest`][awards-interest] | [`MemoryInterestCalculator`][memory-interest-calculator] Awards interest to account earned during a period of days |
| [`CalculatesInterestRates`][calculates-interest-rates] | [`IncomeBasedRate`][income-based-rate] Calculates new Interest Account interest rate using User monthly income |
| [`OpensInterestAccounts`][opens-interest-accounts] | [`MemoryAccountManager`][memory-account-manager]  Opens Interest Account using calculated interest rate |
| [`ProvidesInterestAccounts`][provides-interest-accounts] | [`MemoryAccountManager`][memory-account-manager] Provides Interest Accounts from memory |
| [`ProvidesUserInformation`][provides-users-information] | [`ChipUserApi`][chip-user-api] Provides user information from the Chip HTTP API |

### Chip User API

The Chip User API provides user data over an http interface. You will need one
or many packages that
[provide implementations of `psr/http-{client,message,factory}`][http-clients]
to use this user information provider, such as [`guzzle`][guzzle].

```php
new ChipUserApi(
    Psr\Http\Client\ClientInterface,
    Psr\Http\Message\ServerRequestFactoryInterface,
    'https://stats.dev.chip.test/'
);
```

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
[http-clients]: https://packagist.org/providers/psr/http-client-implementation
[guzzle]: https://github.com/guzzle/guzzle
[awards-interest]: src/Interest/AwardsInterest.php
[memory-interest-calculator]: src/Interest/MemoryInterestCalculator.php
[calculates-interest-rates]: src/Interest/CalculatesInterestRates.php
[income-based-rate]: src/Interest/IncomeBasedRate.php
[opens-interest-accounts]: src/Interest/OpensInterestAccounts.php
[memory-account-manager]: src/Interest/MemoryAccountManager.php
[provides-interest-accounts]: src/Interest/ProvidesInterestAccounts.php
[provides-users-information]: src/Interest/ProvidesUserInformation.php
[chip-user-api]: src/Interest/ChipUserApi.php
