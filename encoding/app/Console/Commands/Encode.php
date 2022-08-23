<?php

namespace App\Console\Commands;
use FFMpeg;
use Config;
use Illuminate\Http\File;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage; 
use FFMpeg\Filters\Video\VideoFilters;
use FFMpeg\Format\ProgressListener\AbstractProgressListener;
use ProtoneMedia\LaravelFFMpeg\FFMpeg\ProgressListenerDecorator;

class Encode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'custom:encode';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Encodage des vidéos de myYoutube';

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
     * @return mixed
     */
    public function handle()
    {
        $url = 'http://127.0.0.1:8000/api';
        $endpoint = '/encode';
        $client = new \GuzzleHttp\Client(['http_errors' => false]);
        $response = $client->request('GET', $url . $endpoint);
        $results = json_decode($response->getBody(), true);

        $height = [144, 240, 360, 480, 720, 1080];
        $width = [256, 426, 640, 848, 1280, 1920];

        foreach ($results['data'] as $result) {
            shell_exec('cp -rf ' . $result['source'] . ' ' . storage_path() . '/app/' . $result['name'] . '.mp4');
            
            for ($i = 0; $i <= 5 ; $i++) {

        
                $video = FFMpeg::open($result['name'] . '.mp4');
                $dimension = $video->getVideoStream()->getDimensions()->getHeight();
                $format = new \FFMpeg\Format\Video\X264('aac');
                $endpoint2 = '/video/' . $result['video_id'];


                if ($dimension >= $height[$i]) {
                    $video->export()
                        ->inFormat($format)
                        ->addFilter(function (VideoFilters $filters) use ($height, $width, $i) {
                            $filters->resize(new \FFMpeg\Coordinate\Dimension($height[$i], $height[$i]));
                        })
                        ->save($result['name'] . '_' . $height[$i] . '.mp4');

                    $datas = ['format' => $height[$i], 'file' => storage_path() . '/app/' . $result['name'] . '_' . $height[$i] . '.mp4', 'file_name' => $result['name'] . '_' . $height[$i] . '.mp4'];
                    $test = $client->request('PATCH', $url . $endpoint2, [
                                \GuzzleHttp\RequestOptions::JSON  => $datas
                    ]);
                }
            }
            $client = new \GuzzleHttp\Client(['http_errors' => false]);
            $response = $client->request('GET', 'http://127.0.0.1:8001/api/encode/' . $result['email']);
            $results = json_decode($response->getBody(), true);
        }
    }
}
