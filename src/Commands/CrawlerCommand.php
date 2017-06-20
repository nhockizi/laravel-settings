<?php

namespace Kizi\Settings\Commands;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use Illuminate\Console\Command;
use Kizi\Settings\Auth\Database\Category;
use Kizi\Settings\Auth\Database\CategoryNews;
use Kizi\Settings\Auth\Database\CrawlerDb;
use Kizi\Settings\Auth\Database\News;
use Symfony\Component\DomCrawler\Crawler;

class CrawlerCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'crawler {function} {--code=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawler form url';

    /**
     * Install directory.
     *
     * @var string
     */
    protected $directory = '';

    public $news;

    public $category;

    public $categoryNews;
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function __construct(News $news, Category $category, CategoryNews $categoryNews)
    {
        $this->news         = $news;
        $this->category     = $category;
        $this->categoryNews = $categoryNews;
        parent::__construct();
    }
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $function = $this->argument('function');
        if (method_exists($this, $function)) {
            // $this->setUp();
            call_user_func([$this, $function]);
        }
        // $this->initCrawler();
    }
    public function getCategory()
    {
        dd('ssss');
    }
    private function crawlerLink($url)
    {
        set_time_limit(0);
        // $proxy   = array();
        // $proxy[] = '47.89.53.92:3128';
        // $proxy[] = '1.239.89.210:8080';
        // $proxy[] = '113.252.236.96:8080';
        // $proxy[] = '222.82.222.242:9999';
        // $proxy[] = '92.99.142.59:8118';
        // $proxy[] = '80.1.116.80:80';
        // $rand    = rand(0, 5);
        try {
            $config = [
                // 'proxy'          => [
                //     'http' => $proxy[$rand],
                // ],
                'verify'         => false,
                'decode_content' => false,
            ];
            $client   = new Client($config);
            $response = $client->request('GET', $url);
            if ($response->getStatusCode() == '200') {
                return $response->getBody()->getContents();
            }
        } catch (TransferException $e) {
            return 'Caught exception: ' . $e->getMessage();
        }
    }
    public function getContent()
    {
        set_time_limit(0);
        $code        = $this->option('code');
        $dataCrawler = CrawlerDb::where(['active' => 1, 'code' => $code])->first();
        if (!isset($dataCrawler)) {
            $this->info('Got empty result processing the dataset!');
            return false;
        }

        $parameUrl = str_replace('{page}', $dataCrawler->number_run, $dataCrawler->parame_url);
        $url       = $dataCrawler->url . $parameUrl;
        $html      = $this->crawlerLink($url);
        $crawler   = new Crawler($html);
        $filter    = $crawler->filter($dataCrawler->item);
        $result    = array();
        if (iterator_count($filter) > 0) {
            foreach ($filter as $i => $content) {
                $cralwer   = new Crawler($content);
                $urlDetail = $cralwer->filter($dataCrawler->url_detail)->attr('href');
                if ($this->checkUrl($urlDetail) === false) {
                    $urlDetail = $dataCrawler->url . $urlDetail;
                }
                $htmlDetail = $this->crawlerLink($urlDetail);
                $detail     = new Crawler($htmlDetail);
                $result[$i] = array(
                    'images'      => null,
                    'title'       => $cralwer->filter($dataCrawler->title)->text(),
                    'description' => $cralwer->filter($dataCrawler->description)->text(),
                    'detail'      => $detail->filter($dataCrawler->detail)->text(),
                );
                if ($dataCrawler->images !== '') {
                    $srcImages = $cralwer->filter($dataCrawler->images)->attr('src');
                    $srcImages = $dataCrawler->url . str_replace($dataCrawler->url, '', $srcImages);
                    if ($dataCrawler->images != '' && $this->checkUrl($srcImages) === true) {
                        $result[$i]['images'] = $srcImages;
                    }
                }
            }
            krsort($result);
            $this->news->insert($result);
            CrawlerDb::where(['id' => $dataCrawler->id])->decrement('number_run', 1);
            // if ($dataCrawler->number_run > 1) {
            //     sleep(10);
            //     $this->call('crawler', ['function' => 'getContent', '--code=abc1']);
            // }
        } else {
            $this->info('Got empty result processing the dataset!');
        }
    }
    public function checkUrl($url)
    {
        $file_headers = @get_headers($url);
        if (!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
            return false;
        }
        return true;
    }
}