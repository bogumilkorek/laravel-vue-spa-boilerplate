# Laravel & Vue.js Single Page Application Boilerplate
Everything you need to start building amazing apps!

## 1. Features
- Built on top of Laravel 5.6, Vue.js 2.5, and Vuetify 1.0
- Admin panel: CRUD for pages with JWT Authentication (using RS256 algorithm), drag & drop reordering and option to show desired pages in navbar
- JWT is also verified by frontend, as well as IP address from token's payload
- App can be easily translated to desired language using vuex-i18n and translation strings. Just rename *resources/assets/js/translationsPL.js* according to your language and translate it. You'll also need to change line 33 and 34 in *app.js*.
- Image dropzone
- WYSIWYG (Quill) with image upload
- Image gallery (lightbox)

## 2. Installation
1. Clone repository:<br />
`git clone https://github.com/bogumilkorek/laravel-vue-spa-boilerplate && cd laravel-vue-spa-boilerplate`

2. Rename *.env.example* to *.env* and insert your database connection data as well as random key (JWT_PASSPHRASE)

3. Generate Private & Public Key using previously typed JWT_PASSPHRASE:<br />
`openssl genrsa -passout pass:YOUR_JWT_PASSPHRASE -out storage/jwt/private.pem -aes256 4096`<br />
`openssl rsa -passin pass:YOUR_JWT_PASSPHRASE -pubout -in storage/jwt/private.pem -out public/jwt/public.pem`

4. Install Composer dependencies:<br />
`composer install`

5. Generate app key:<br />
`php artisan key:generate`

6. Generate JWT Secret:<br />
`php artisan jwt:secret`

7. Run DB migrations:<br />
`php artisan migrate`

8. If you want to fill DB with example values run seeders:<br />
`php artisan db:seed`

9. Install Node dependencies:<br />
`yarn install`

10. Run API tests:<br />
`vendor/bin/phpunit`

11. Test Vue components (still in development):<br />
`yarn test`

12. Compile assets:<br />
`yarn dev`

13. Navigate to localhost/laravel-vue-spa-boilerplate and check if everything works fine

14. **Admin panel: /admin, login: admin, password: secret**

## 3. Todo
- Code refactoring
- Proper Vue components tesing

## 4. License
Both personal and commercial use allowed without any restrictions.<br />
Feel free to ask me any questions.<br />
**Code reviews are always much appreciated :)**
