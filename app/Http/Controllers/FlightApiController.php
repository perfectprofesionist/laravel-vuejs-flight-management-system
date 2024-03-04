<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FlightApiController extends Controller
{
    //Function to get the token for the authorization
    public function getToken()
    {

        
        $amadeusApiKey = ENV('AMADEUS_API_KEY');
        $amadeusApiSecret = ENV('AMADEUS_API_SECRET');

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://test.api.amadeus.com/v1/security/oauth2/token',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => 'grant_type=client_credentials&client_id='.$amadeusApiKey.'&client_secret='.$amadeusApiSecret.'',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $responseData = json_decode($response, true);
        $access_token = $responseData['access_token'];
        return $access_token;

    }
    //Method To get the fligth Data From the Laravel
    public function getFlightData(Request $request){
                // Base URL for the Amadeus API
               
        $baseUrl = 'https://test.api.amadeus.com/v2';

        // Access token obtained from response variable
        $accessToken = $this->getToken();

        // Parameters for the request
        $params = [
                    'originLocationCode'      => $request->origin,
                    'destinationLocationCode' => $request->destination,
                    'departureDate'           => $request->departureDate, // Replace with your desired date format
                    'adults'                  => $request->adults
                  ];

        // Initialize curl session
        $ch = curl_init();

        // Set curl options
        curl_setopt_array($ch, [
            CURLOPT_URL => $baseUrl . '/shopping/flight-offers?' . http_build_query($params),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $accessToken,
            ],
        ]);

        // Execute the request
        $response = curl_exec($ch);

        // Close the curl session
        curl_close($ch);

        // Decode the JSON response
        $responseData = json_decode($response, true);
        // return $responseData;
        // Prepare data to send
        $dataToSend = [
            'data' => $responseData,
            'dictionaries' => $responseData,
        ];

        // Return the data
        return response()->json($dataToSend,200);
    }
}

