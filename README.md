# Baleen's Back to the Future Memorial Plugin
A plugin to commemorate October 21, 2015 - #BackToTheFuture Day.

If Baleen detects that you're migrating past October 21, 2015 you'll see a special message in your 
console.

## Installation (Composer)

```php
composer install baleen/b2tf:dev-master
```

## Usage

You must have at least two migration files:

1. One with a timestamp before October 21, 2015
2. Another one with a timestamp after that date.

Then just migrate to see the message!  
