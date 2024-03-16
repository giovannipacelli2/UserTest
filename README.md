## Sviluppo front e back end di una web app che permetta lettura, modifica, creazione ed eliminazione di un utente 

## **Prerequisiti:**

## back-end:

* aver installato PHP e MySql ( es. XAMPP )
* installare `composer` come dependecy manager
* spostarsi nella cartella `/server` ed avviare `composer` con il comando:
    
        composer install

* creare apposito file `.ENV` per l’accesso al DB

		Immettere i propri dati nel file .ENV
        
		DB_NAME=workpipe
		DB_USER=[username]
        DB_PASSWORD=[password]
        DB_HOST=[host]		// es. Localhost -> 127.0.0.1

* per visualizzare gli errori, commentare le 3 righe di codice indicate nel file `error-handler.php` nella cartella `./core`.

* per avviare il server lanciare il comando:

        PHP -S localhost:8888

    il numero di porta non deve essere necessariamente `8888` ma deve coincidere con la variabile d'ambiente nel front-end

</br>

### **Linguaggi utilizzati:** 

</br>

* PHP, MySql.

## front-end:

* aver installato `npm`

* spostarsi in `/client` ed installare tutte le dipendenze tramite `npm`

* creare apposito file `.ENV` e valorizzare `REACT_APP_SERVER` con l'url del server es:

        REACT_APP_SERVER="http://localhost:8888/"

Comando di installazione:

    npm install

Comando di avvio:

    npm run start

## **Linguaggi e framework utilizzati:** 

* HTML, CSS, JavaScript, React.


## **Info specifiche:** 

Le info sulle specifiche di front e back-end sono presenti rispettivamente nei file README in `./client` e `./server`