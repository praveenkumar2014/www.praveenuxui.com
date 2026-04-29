<?php
namespace App\Services;

use GuzzleHttp\Client;

class LinkedInService {
    private $client;
    private $clientId;
    private $clientSecret;
    private $redirectUri;

    public function __construct() {
        $this->client = new Client(['base_uri' => 'https://api.linkedin.com/v2/']);
        $this->clientId = $_ENV['LINKEDIN_CLIENT_ID'];
        $this->clientSecret = $_ENV['LINKEDIN_CLIENT_SECRET'];
        $this->redirectUri = $_ENV['LINKEDIN_REDIRECT_URI'] ?? '';
    }

    public function postUpdate($accessToken, $urn, $text) {
        try {
            $response = $this->client->post('ugcPosts', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'X-Restli-Protocol-Version' => '2.0.0',
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'author' => "urn:li:person:$urn",
                    'lifecycleState' => 'PUBLISHED',
                    'specificContent' => [
                        'com.linkedin.ugc.ShareContent' => [
                            'shareCommentary' => [
                                'text' => $text
                            ],
                            'shareMediaCategory' => 'NONE'
                        ]
                    ],
                    'visibility' => [
                        'com.linkedin.ugc.MemberNetworkVisibility' => 'PUBLIC'
                    ]
                ]
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
