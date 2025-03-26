
# Laravel PC Webshop
Overleaf: https://www.overleaf.com/1638313976vkvyswthwnqy#910e42

## Tehnologije

- **Laravel 10+**
- **MySQL**
- **Sanctum** za autentifikaciju API-ja
- **Tailwind CSS** za stilizaciju
- **JavaScript / Alpine.js** za interaktivnost
- **Vite** za frontend bundling
- **SweetAlert2** za korisničke interakcije
- **Laravel WebSockets** za real-time notifikacije
- **DomPDF** za generisanje PDF faktura

composer install

Kopirati .env.example u .env fajl:

php artisan migrate

php artisan db:seed

Prijava
Za prijavu u aplikaciju, koristiti sljedeće korisničke naloge:

Administrator:

Email: mehmedbossnic@gmail.com

Lozinka: admin

Korisnici (1-20):

Email: user1@mailx.com do user20@mailx.com

Lozinka: user

Prilikom registracije korisnici će primiti email obavijesti. Za testiranje, koristite svoju pravu email adresu prilikom registracije. (ako se registrujete, u bazi samo iskopirajte neki datum verifikacije)

Aplikacija koristi Laravel mail sistem, pa se preporučuje konfiguracija za SMTP server u .env fajlu (npr. Mailtrap, Gmail ili neki drugi SMTP servis). U env je podešen moj gmail, trebalo bi da radi, nisam siguran treba li i xampp podešavati jer je moj već bio podešen.


npm install

npm run dev

php artisan serve
