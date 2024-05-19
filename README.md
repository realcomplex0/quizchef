# quizchef

An interactive online multiplayer quiz game

## List of developers

1. Kristaps Štāls
1. Matīss Kristiņš

## ⚠️ Important

All commands should be launched using Windows WSL

## Launching the project
Laravel Sail is used for local development [(Link)](https://laravel.com/docs/11.x/sail#introduction)

Launching the project:

```
./vendor/bin/sail up -d
```

Enter the executable shell:

```
./vendor/bin/sail bash
```

Inside the shell the front-end server can be launched with:

```
npm run dev
```

Stopping the project:

```
./vendor/bin/sail stop
```

Deleting the containers:

```
./vendor/bin/sail down
```

The app should be in: [http://localhost](http://localhost)


## Laravel routing

To view the list of routes:

```
php artisan route:list
```

To cache the newest list of routes (it might be necessary to restart vite server)

```
php artisan route:cache
```

To clear the list of routes

```
php artisan route:clear
```

## Websocket setup

To launch the Websocket server, use:

```
php artisan reverb:start
```