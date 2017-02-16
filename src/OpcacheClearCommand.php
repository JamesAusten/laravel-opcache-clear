<?php

namespace MicheleCurletta\LaravelOpcacheClear;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;

class OpcacheClearCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'opcache:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear OpCache';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $client = new Client([
            'base_uri' => config('app.url', 'http://localhost')
        ]);

        $encryptedToken = Crypt::encrypt(config('app.key'));

        $response = $client->request('GET', '/opcache-clear', [
            'query' => [
                'token' => $encryptedToken
            ]
        ]);

        $body = json_decode($response->getBody());

        if ($body->result) {
            $this->info('PHP OPCache cleared successfully');
        } else {
            $this->error('Failed to clear PHP OPCache');
        }

    }
}
