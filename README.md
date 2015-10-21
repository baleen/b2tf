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
2. Another one with a timestamp after that date. You can create one by running `vendor/bin/baleen create`.

Then just migrate (`vendor/bin/baleen migrate`) to see the message!
  
## Q&A

#### I don't have any migrations prior to October 21st. How can I test this?
No worries, easy fix: just take the DeLorean back to the past and choose Baleen CLI as your migrations framework! 

Alternative:  
 
1. Add a new class in your migrations directory. The filename and class-name must coincide, make it something old
like for example `v19851026090000`. 
2. Make it implement `Baleen\Migrations\Migration\MigrationInterface`.
3. You now have a migration in the past. Now create one in the future by running `vendor/bin/baleen create`.
4. Assuming you also followed the installation instructions above, you're good to go!
