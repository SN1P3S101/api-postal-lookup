<?php

    //! ICT-Hoogeveen | Wesley Schreur | SN1P3S
    // Iets waar ik mee bezig ben, en voor nu wil ik dit openbaar maken zodat iedereen dit gemakkelijk kan uitproberen :)
    // Hier is je supergeheime api sleutel: ICT-96PpYACPkT?TEw6MQfmH-e4p21tNR/86FYpeNU8tNSbIpmRDS5-EpGF?5iwA

    header('Content-Type: application/json');

    $key = 'ICT-96PpYACPkT?TEw6MQfmH-e4p21tNR/86FYpeNU8tNSbIpmRDS5-EpGF?5iwA';
    $postal = isset($_GET['postal']) ? $_GET['postal'] : '';
    $postal = validate_input($postal, 6);
    $number = isset($_GET['number']) ? $_GET['number'] : '';
    $number = validate_input($number, 5);

    function validate_input($input, $max_length) {

        $input = trim($input);

        if (strlen($input) > $max_length) {

            return false;

        }
        
        if (!preg_match('/^[a-zA-Z0-9]+$/', $input)) {

            return false;

        }

        return $input;
    }

    if (empty($key)) {

        http_response_code(400);

        echo json_encode([
            'status' => 'error',
            'message' => 'Geen API-sleutel meegegeven.'
        ]);

        exit;

    }

    if ($key !== $key) {

        http_response_code(401);

        echo json_encode([
            'status' => 'error',
            'message' => 'Ongeldige API-sleutel meegestuurd.'
        ]);

        exit;

    }

    if ($postal === false) {

        http_response_code(400);

        echo json_encode([
            'status' => 'error',
            'message' => 'Ongeldige postcode ingevoerd.'
        ]);

        exit;

    }

    if ($number === false) {

        http_response_code(400);

        echo json_encode([
            'status' => 'error',
            'message' => 'Ongeldig huisnummer ingevoerd.'
        ]);

        exit;

    }

    if (empty($postal) || empty($number)) {

        http_response_code(400);

        echo json_encode([
            'status' => 'error',
            'message' => 'Je moet zowel een postcode als een huisnummer opgeven.'
        ]);

        exit;

    }

    $postal = strtoupper(str_replace(' ', '', $postal));
    $url = 'https://api.pdok.nl/bzk/locatieserver/search/v3_1/free?' . http_build_query([
        'q' => 'postcode:' . $postal . ' AND huisnummer:' . $number,
        'rows' => 1,
        'fl' => 'straatnaam,woonplaatsnaam'
    ]);
    $response = file_get_contents($url);

    if ($response === FALSE) {

        http_response_code(500);

        echo json_encode([
            'status' => 'error',
            'message' => 'Er is een fout opgetreden bij het ophalen van de adresgegevens.'
        ]);

        exit;

    }

    $data = json_decode($response, true);

    if (empty($data['response']['docs'])) {

        http_response_code(404);

        echo json_encode([
            'status' => 'error',
            'message' => 'Geen adresgegevens gevonden voor de opgegeven postcode en huisnummer.'
        ]);

        exit;

    }

    $adres = $data['response']['docs'][0];
    $postal_combined = $postal;
    $postal = substr($postal, 0, 4) . ' ' . substr($postal, 4);

    echo json_encode([
        'status' => true,
        'straatnaam' => $adres['straatnaam'],
        'huisnummer' => $number,
        'postcode' => $postal_combined,
        'plaats' => $adres['woonplaatsnaam'],
        'land' => 'Nederland',
        'text' => $adres['straatnaam'] . ' ' . $number . ', ' . $postal . ' ' . $adres['woonplaatsnaam']
    ]);
    
?>
