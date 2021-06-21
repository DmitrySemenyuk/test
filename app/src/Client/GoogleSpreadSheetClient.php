<?php

declare(strict_types=1);

namespace App\Client;

use App\DTO\ProductDTO;
use App\Mapper\ProductMapper;
use \Google_Client;
use \Google_Service_Sheets;
use \Google_Service_Sheets_Spreadsheet;

class GoogleSpreadSheetClient
{
    public function __construct(protected ProductMapper $mapper)
    {
    }

    /**
     * @param ProductDTO[] $products
     */
    public function saveSheet(array $products): void
    {
        $service = $this->createService();
        $spreadsheetId = $this->createSpreadsheet($service);

        $preparedRows = $this->mapper->transformDtoToArray($products);
        $this->saveRows($service, $spreadsheetId, $preparedRows);
    }

    protected function createService(): Google_Service_Sheets
    {
        $client = $this->getClient();

        return new Google_Service_Sheets($client);
    }

    protected function getClient(): Google_Client
    {
        $scopes = [
            Google_Service_Sheets::SPREADSHEETS_READONLY,
            Google_Service_Sheets::DRIVE_FILE,
            Google_Service_Sheets::DRIVE,
        ];

        $client = new Google_Client();
        $client->setApplicationName('Google Sheets API PHP Quickstart');
        $client->setScopes($scopes);
        $client->setAuthConfig(__DIR__ . '/../../config/googleSheets/credentials.json');
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        // Load previously authorized token from a file, if it exists.
        // The file token.json stores the user's access and refresh tokens, and is
        // created automatically when the authorization flow completes for the first
        // time.
        $tokenPath = __DIR__ . '/../../config/googleSheets/token.json';
        if (file_exists($tokenPath)) {
            $accessToken = \json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);
        }

        // If there is no previous token or it's expired.
        if ($client->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                // Request authorization from the user.
                $authUrl = $client->createAuthUrl();
                printf("Open the following link in your browser:\n%s\n", $authUrl);
                print 'Enter verification code: ';
                $authCode = trim(fgets(STDIN));

                // Exchange authorization code for an access token.
                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                $client->setAccessToken($accessToken);

                // Check to see if there was an error.
                if (array_key_exists('error', $accessToken)) {
                    throw new \Exception(join(', ', $accessToken));
                }
            }
            // Save the token to a file.
            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0700, true);
            }
            file_put_contents($tokenPath, \json_encode($client->getAccessToken()));
        }
        return $client;
    }

    protected function createSpreadsheet(Google_Service_Sheets $service): string
    {
        $spreadsheet = new Google_Service_Sheets_Spreadsheet(['properties' => ['title' => 'custom title']]);
        $spreadsheet = $service->spreadsheets->create($spreadsheet, ['fields' => 'spreadsheetId']);

        return $spreadsheet->spreadsheetId;
    }

    protected function saveRows(\Google_Service_Sheets $service, string $spreadsheetId, array $rows): void
    {
        $body = new \Google_Service_Sheets_ValueRange(['values' => $rows]);
        $params = ['valueInputOption' => 'RAW'];
        $result = $service->spreadsheets_values->update($spreadsheetId, 'A1', $body, $params);

        printf("%d cells updated.", $result->getUpdatedCells());
    }
}
