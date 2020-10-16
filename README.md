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

### Notes

* All numeric values are used as integers to avoid floating point math errors
that are possible with PHP. A percentage value should be passed as an integer
representing the number of 1/100ths. For example, `1` is equivalent to `0.01%`,
`450` is equivalent to `4.50%` and `10000` is equivalent to `100.00%`.

### Income Based Rates

Income based rates are calculated using a minimum required amount, with a
default rate for any user that does not provide income information or does not
meet any minimum requirements.

```php
new IncomeBasedRate(
    $default = 50,
    $rates = [
      $minimum = 0 => $rate = 093,
      5000 => 102,
    ]
);
```

### Memory Interest Calculator

Interest is calculated based on the Account balance, Account interest rate and
the award frequency -- for example, an award frequency of `3` will award 3 days
of interest on the balance. Any awards less than 1 penny (`>0 <1`) will be held
until the total pending awards is at least 1 penny.

```php
new MemoryInterestCalculator(
    $awardFrequency = 3,
    Lcobucci\Clock\Clock
);
```

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
