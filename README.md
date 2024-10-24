# Postcode en Huisnummer Verificatie API(PHP)
Een eenvoudige API voor het verifiëren van een Nederlands adres op basis van een postcode en huisnummer, waarbij de straatnaam en plaatsnaam worden opgehaald.

**Let op: De API-sleutel is hardcoded en zichtbaar voor iedereen, dus het is vrij toegankelijk voor gebruik. Deze API is bedoeld om openbaar te zijn en uit te proberen!**

## API Endpoint
De API controleert of een postcode en huisnummer overeenkomen met een bestaand adres in Nederland.

```http
GET /?key={api_key}&postal={postal_code}&number={house_number}
```

### Parameters:
- **key**: Je API-sleutel. Dit voorkomt ongeautoriseerde toegang, hoewel deze publiek is.
- **postal**: De postcode die je wilt verifiëren (zonder spaties).
- **number**: Het huisnummer dat hoort bij de opgegeven postcode.

- ### Voorbeeld:
```http
GET http://api.postcode-verify.ict-hoogeveen.nl/?key=<KEY>&postal=1234AB&number=10
```

## Responses
De API retourneert een JSON-object met de volgende mogelijke responses:

### 1. Ongeldige API-sleutel
- **Status**: `401 Unauthorized`
- **Response**:
    ```json
    {
        "status": "error",
        "message": "Ongeldige API-sleutel meegestuurd."
    }
    ```
- **Betekenis**: De opgegeven API-sleutel is onjuist of ontbreekt. Controleer de sleutel.

### 2. Geen postcode of huisnummer meegegeven
- **Status**: `400 Bad Request`
- **Response**:
    ```json
    {
        "status": "error",
        "message": "Je moet zowel een postcode als een huisnummer opgeven."
    }
    ```
- **Betekenis**: Eén van de vereiste parameters 'postal' of 'number' is niet meegegeven. Vul beide correct in.

### 3. Ongeldige postcode
- **Status**: `400 Bad Request`
- **Response**:
    ```json
    {
        "status": "error",
        "message": "Ongeldige postcode ingevoerd."
    }
    ```
- **Betekenis**: De ingevoerde postcode voldoet niet aan het verwachte formaat (bijv. te lang of bevat ongeldige tekens).

### 4. Ongeldig huisnummer
- **Status**: `400 Bad Request`
- **Response**:
    ```json
    {
        "status": "error",
        "message": "Ongeldig huisnummer ingevoerd."
    }
    ```
- **Betekenis**: Het ingevoerde huisnummer voldoet niet aan het verwachte formaat (bijv. te lang of bevat ongeldige tekens).

### 5. Adres niet gevonden
- **Status**: `404 Not Found`
- **Response**:
    ```json
    {
        "status": "error",
        "message": "Geen adresgegevens gevonden voor de opgegeven postcode en huisnummer."
    }
    ```
- **Betekenis**: De opgegeven postcode en huisnummer leverden geen adresgegevens op in de database.

### 6. Succesvolle verificatie van het adres
- **Status**: `200 OK`
- **Response**:
    ```json
    {
        "status": true,
        "straatnaam": "Straatnaam",
        "huisnummer": "10",
        "postcode": "1234AB",
        "plaats": "Plaatsnaam",
        "land": "Nederland",
        "text": "Straatnaam 10, 1234 AB Plaatsnaam"
    }
    ```
- **Betekenis**: Het opgegeven adres is succesvol geverifieerd. De straatnaam, plaatsnaam en het volledige adres worden geretourneerd.

## Installatie

1. **Clone de repository:**
    ```bash
    git clone https://github.com/SN1P3S101/api-postal-lookup.git
    ```

2. **Voer de API lokaal uit met PHP:**
    - Gebruik PHP's ingebouwde webserver:
    ```bash
    php -S localhost:8000
    ```

3. **Zorg voor een actieve internetverbinding:**
    - De API heeft internettoegang nodig om de adresgegevens op te halen via de PDOK API.

4. **Test de API:**
    - Gebruik een tool zoals `curl` of open je browser om een GET-verzoek te sturen:
    ```bash
    curl "http://localhost:8000/?key=JOUW_API_SLEUTEL&postal=1234AB&number=10"
    ```

## Vereisten

- **PHP 8.2 of hoger:** Zorg ervoor dat PHP correct is geïnstalleerd.
- **Internettoegang:** De API heeft een werkende internetverbinding nodig voor het ophalen van adresgegevens via de externe API.
