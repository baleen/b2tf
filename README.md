# Baleen's Back to the Future Memorial Plugin
A plugin to commemorate October 21, 2015 - #BackToTheFuture Day.

If Baleen detects that you're migrating past October 21, 2015 you'll see a special message in your 
console.

## Installation (Composer)

```php
composer install baleen/b2tf:dev-master
```

Then run `vendor/bin/baleen init`. This will generate a file called `.baleen.yml`

Open that file for editing and add the following lines at the beginning or end:

```yaml
providers:
    b2tf: \Baleen\B2tf\BackToTheFutureProvider
```

## Usage

You must have at least two migration files:

1. One with a timestamp before October 21, 2015
2. Another one with a timestamp after that date.

Then just migrate to see the message!  
