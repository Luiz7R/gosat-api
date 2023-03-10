<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

### Instale as Dependencias com:

```
composer install
```


### Crie Seu Banco de Dados:

* e coloque o nome na váriavel do env chamada:
* DB_DATABASE
* e suas credencias do seu banco de dados nas váriaveis:
* DB_USERNAME=seuusuario
* DB_PASSWORD=suasenha

* Após configurar rode o comando:
``` 
php artisan migrate 

```

Após isso já pode rodar o seguinte comando:

``` 
php artisan serve
```

Após o servidor estiver rodando, já pode fazer as requisições usando o arquivo disponibilizado do postman:

<a href="https://documenter.getpostman.com/view/12724363/2s93JtP3UJ">Endpoints</a>

e o Arquivo também está no repositório.

Já tem as opções para os três CPFs:

```
111.111.111.11
123.123.123.12
222.222.222.22
```

