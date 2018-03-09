# Laravel & Vue.js Single Page Application Boilerplate
Everything you need to start building amazing apps!

## 1. Features
- Built on top of Laravel 5.6, Vue.js 2.5, and Vuetify 1.0
- Admin panel: CRUD for pages with JWT Authentication (using RS256 algorithm), drag & drop reordering and option to show desired pages in navbar
- JWT is also verified by frontend, as well as IP address from token's payload
- App can be easily translated to desired language using vuex-i18n and translation strings. Just rename *resources/assets/js/translationsPL.js* according to your language and translate it. If you need English language only - just delete this file.
- Image dropzone
- WYSIWYG (Quill) with image upload
- Lightbox

## 2. Installation
- Clone repository:<br />
`git clone https://github.com/bogumilkorek/laravel-vue-spa-boilerplate && cd laravel-vue-spa-boilerplate`

- Rename *.env.example* to *.env* and insert your database connection data as well as random key (JWT_PASSPHRASE)

- Generate Private & Public Key using previously typed JWT_PASSPHRASE:<br />
`openssl genrsa -passout pass:YOUR_JWT_PASSPHRASE -out storage/jwt/private.pem -aes256 4096`<br />
`openssl rsa -passin pass:YOUR_JWT_PASSPHRASE -pubout -in storage/jwt/private.pem -out public/jwt/public.pem`

- Install Composer dependencies:<br />
`composer install`

- Generate app key:<br />
`php artisan key:generate`

- Generate JWT Secret:<br />
`php artisan jwt:secret`

- Run DB migrations:<br />
`php artisan migrate`

- If you want to fill DB with example values run seeders:<br />
`php artisan db:seed`

- Install Node dependencies:<br />
`yarn install`

- Run API tests:<br />
`vendor/bin/phpunit`

- Test Vue components (still in development):<br />
`yarn test`

- Compile assets:<br />
`yarn dev`

- Navigate to localhost/laravel-vue-spa-boilerplate and check if everything works fine

- **Admin panel: /admin, login: admin, password: secret**

## 3. Todo
- Code refactoring
- Proper Vue components tesing

## 4. License
Both personal and commercial use allowed without any restrictions.<br />
Feel free to ask me any questions.<br />
**Code reviews are always much appreciated :)**
