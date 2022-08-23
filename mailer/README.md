# Micro-service Mailer

## Préparation

- ````https://myaccount.google.com/security?gar=1````

- ````Accès moins sécurisé des applications````

- ````Activer l’accès (déconseillé)````

- ````Paramètre "Autoriser les applications moins sécurisées" désactivé -> Mettre sur ON````

## Dockerfile

- ````cd postfix ````

- ````docker build -t postfix/postfix . ````

- ````docker run -d --name postfix -p "25:25" -e SMTP_SERVER=smtp.gmail.com -e SMTP_USERNAME=votre_email -e SMTP_PASSWORD=votre_password -e SERVER_HOSTNAME=my.youtube.com postfix/postfix````

## Installation

- ````cp .env.example .env````

- ````composer install````

## Lancemenent

- ````php artisan serve --port=8001````
