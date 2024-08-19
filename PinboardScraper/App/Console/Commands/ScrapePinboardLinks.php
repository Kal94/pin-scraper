<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use App\Models\Link;
use Symfony\Component\DomCrawler\Crawler;

class ScrapePinboardLinks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:pinboard';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape Pinboard for specific tags and store the links in the database';

    /**
     * Execute the console command.
     * 
     * This method is where the magic is executed.
     * It performs the scraping process, filters the data, and stores it in the database.
     */
    public function handle()
    {
        $client = new Client();
        $response = $client->get('https://pinboard.in/u:alasdairw?per_page=120');

        if ($response->getStatusCode() === 200) {
            $html = (string) $response->getBody();
            $crawler = new Crawler($html);

            // Define the tags that we are interested in scraping.
            $tagsToSearch = ['laravel', 'vue', 'vue.js', 'php', 'api'];

            // Filter and iterate through the HTML elements that have the class 'bookmark_title'.
            $crawler->filter('a.bookmark_title')->each(function ($node) use ($tagsToSearch) {
                $linkUrl = $node->attr('href');
                $linkTitle = $node->text();

                // Find the closest parent element with the class 'bookmark' to get more details.
                $bookmarkContainer = $node->closest('.bookmark');
                $linkComments = $bookmarkContainer->filter('div.description')->text();
                $linkTags = $bookmarkContainer->filter('a.tag')->each(function ($tagNode) {
                    return $tagNode->text();
                });

                // Check if the link's tags match any of the tags we're interested in.
                if (array_intersect($tagsToSearch, $linkTags)) {
                    $isLive = $this->checkUrl($linkUrl);

                    // Insert or update the link in the database.
                    Link::updateOrCreate(
                        ['url' => $linkUrl],
                        [
                            'title' => $linkTitle,
                            'comments' => $linkComments,
                            'tags' => json_encode($linkTags),
                            'is_live' => $isLive,
                        ]
                    );
                }
            });
            $this->info('Success!');
        } else {
            $this->error('Error!');
        }
    }

    /**
     * Checking if a URL is live.
     *
     * This helper method sends a HEAD request to the URL to check its status.
     * It returns true if the URL is reachable (status code 200), otherwise false.
     */
    private function checkUrl($url)
    {
        $client = new Client();

        try {
            $response = $client->head($url);
            return $response->getStatusCode() === 200;
        } catch (\Exception $e) {
            return false;
        }
    }
}
