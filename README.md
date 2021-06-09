# BackApp: Backup commandline project built with Laravel-Zero.

A simple command line tool for backingup mysql databases to be run with Laravel's task scheduler.

## Usage

In the directory, the command to run is:

```
php backapp command:backup <dbname> <dbuser> <dbpass>
```

The command handler will use the Env config if you dont specify the options. If you did not add create an env file, you 

## Inspiration

Taken inspiration from [Dzone.com - how to setup automatic db backups in laravel](https://dzone.com/articles/how-to-setup-automatic-db-backup-in-laravel)


About Laravel Zero:

<p align="center">
    <a href="https://laravel-zero.com/"><img title="Laravel Zero" height="100" src="https://raw.githubusercontent.com/laravel-zero/docs/master/images/logo/laravel-zero-readme.png" /></a>
</p>

