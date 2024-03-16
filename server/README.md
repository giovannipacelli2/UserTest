## **Scopo:** 

* Fornire API per gestione dei dati nel database.
* Implementare funzioni CRUD per gestire gli utenti.

</br>

# **Organizzazione dei file:** 

## File `index.php`:

* Richiama tutte le funzioni e file necessari per la connessione al db, prende i dati della URI , richiama `routes.php`, ridireziona il traffico verso il controller specifico

## File `config.php`:

* Crea la configurazione per la connessione al DB utilizzando i dati di accesso presenti in `.ENV`

## Cartella `./app`

* Contiene il file `routes.php`, il quale crea un vero e proprio registro dove vi sono i dati di routing.

* Contiene la cartella `./controllers` con i controller che tramite funzioni dedicate, si occupano di gestire i dati e caricare le risorse necessarie.

* La cartella `./models` contenente le entità con i loro metodi specifici che vanno a richiamare per ogni richiesta la query opportuna.

## La cartella `./core` contiene:

* La cartella `database` contenente `Connection.php` che si occupa di instaurare la connessione vera e propria al DB, il file `QueryBuilder.php` contenente le query specifiche del DB. 

* `ApiFunctions.php` -> Ha tutte le funzioni utili per effettuare controlli e determinate azioni atte a processare i dati.

* `App.php` -> Consente di memorizzare e prendere dati in un registro

* `bootstrap.php` -> incaricata a creare una connessione al DB passando la configurazione a `Connection.php

* `Response.php` -> è una classe che permette di restituire la risposta in formato json.

* `Request.php` -> è una classe che permette di estrarre i dati dalla URI.

* `Router.php` -> Crea la tabella di routing partendo da `routes.php` e smista il traffico verso il controller dedicato con la sua funzione `direct()`.


</br>

# Utilizzo delle API

## Richieste “GET” lettura dati

### Lettura singolo utente:

    GET -> {domain}/user/{id}

### Lettura di tutti gli utenti:

    GET -> {domain}/users

#### Inoltre è possibile utilizzare un limite per gli utenti visualizzati un una pagina es:

    GET -> {domain}/users?limit={limit}

</br>

## Richieste “POST” inserimento dati:

### Inserimento di un nuovo prodotto:

		POST -> {domain}/api/products
        
### Corpo della richiesta: 
    {
        "product_code": "4000",
        "name": "meat",
        "saved_kg_co2":"6"
    }

</br>

### Inserimento di un nuovo utente:
	
    	POST -> {domain}/user

### Corpo della richiesta: 

    {
        "name": "Giacomo",
        "surname": "Russo",
        "email": "russog@gmail.com",
        "birthDate": "1982-01-26"
    }


</br>

## Richieste “PUT” modifica dei dati:

### **N.B** Per tutte le richieste `PUT` è possibile immettere uno o più campi da modificare. Non è necessario rinserire dati che non si vuole modificare.

### Modifica di un utente:

		PUT -> {domain}/user?id={userId}

#### esegue l'update dello user con il codice = {userId}

### Corpo della richiesta: 

    {
        "name": "Giacomo",
        "surname": "Russo",
        "email": "russog@gmail.com",
        "birthDate": "1982-01-26"
    }

#### oppure

    {
        "name": "Giacomo",
        "surname": "Russo",
    }


</br>

## Richieste “DELETE” cancellazione dei dati:

### Cancellazione utente:

		DELETE -> {domain}/user?id={userId}

#### esegue il delete dell'utente con il codice = {userId}