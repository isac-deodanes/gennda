
Aplicacion Hibrida de Agendas y Finanzas

La  aplicacion permite al usuario administrar su agenda personal y laboral, ademas de llevar un control de sus finanzas desde un mismo lugar.

Equipo:

    4 Desarrolladores.

Plataformas soportadas:

    Android

    iOs

    Web


Total de modulos:

    Agenda,
 
    Calendario,
 
    Finanzas,
 
    Historial,
 
    Ayuda y soporte,
 
    Politicas,
 
    Perfil
 

TECNOLOGIAS UTILIZADAS
Frontend

    Ionic 

    Vue.js

    TypeScript

Backend

    Laravel

    Php

    Composer

Base de datos:

    MySql

Erramientas de prueva de api:

    Postman



FUNCIONALIDADES DE CADA COMPONENTE DEL SISTEMA

Agenda

el usuario puede:

    -Crear eventos.
    
    -Editar eventos.
    
    -Eliminae eventos 
    
    -Visualizar eventos en el calendario.
    
    -Registrar eventos de trabajo.
    
    -Registrar eventos familiares.
    
    -Registrar gastos estimados asociado a un evento.


Calendario

El calendario permite a los usuarios:

    -Visualizar los eventos por dia,
    
    -Consultar los eventos programados.



Finanzas

lA aplicacion cuenta con un modulo financiero donde el usuario puede administrar:

    -ingresos
    
    -gastos
    
    -valances
    
    los gastos registrados desde un evento tambien se reflejan automaticamente en el historial financiero.


Historial Financiero

En esta seccion el usuario podra visualizar su:

    -Historial de ingresos,
    
    -Historial de gastos,









COMO INSTALAR PREYECTO

1 Tener istalado Xampp o Laragon

Es necesario tener instalado alguno de los dos software


    -Iniciar Apache
    
    -Iniciar MySql


2 Crear la base datos 

Ingresar a la interfas web phpMyAdmin o localhost y desde ahi crear la base de datos 
es importante nombrarlo con el mismo nombre por el cual esta definido en el archivo .env o visebersa asi como se esta nombrando en el archivo .env asi nombrar la base de datos del API

    Ejemplo: DB_DATABASE=apigennda_db


3 instalciones de depenencias del backend

Una ves ya conectado el proyactoAPI con la base de datos 
instalar las dependendias del backend, entrar a la carpeta API 

ejecutar el comando:

    composer install



4 Ejecutar migraciones

   una ves ya instalado todo y conectado la base de datos 
   ejecutar el comando dentro de la carpeta API: 
   
    php artisan migrate




6 Levantar la API

estando dentro de la carpeta de la api, ejecutar el comando: php artisan serve
y mostrara la url con el puerto.




7 Instalar dependicias del Frontend 

Entrar a la carpeta Gennda 

ejecutar: npm install 

para ejecutar el frontend 

ionic serve

una ves ejecutado todo mostrara la url y el puerto y la aplicacion automaticamente se abrira en el navegador.

