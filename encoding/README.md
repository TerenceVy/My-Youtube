# Micro-Service Encoding

## Pré requis

- ````/!\ Les API myApi et Mailer doivent être lancées````

## Installation

- ````cp .env.example .env````

- ````brew install ffmpeg````

- ````composer install````

## Lancer l'encodage Manuellement

- ````php artisan custom:encode````

## Utilisation de façon Automatique

- ````crontab -e````

- ````* * * * * cd /path/project_dir/encoding && php artisan schedule:run >> >> /dev/null 2>&1````

## Activation de cron sur Mac

- ````Open preferences and go to “Security & Privacy” -> “Privacy”````

- ````Scroll down to “Full Disk Access” on the left side and select it.````

- ````Hit the unlock icon and enter your password````

- ````Hit the “+” sign to add an application````

- ````Type “command+shift+G” to bring up the got to file dialog box (don’t seem able to navigate the /usr directory)````

- ````Enter the full path name to the application (/usr/sbin/cron) and click on “Go”````

- ````Click “Open” It should show up in the list with a check mark next to it. I tried it with a simple csh script in cron and it worked.````
