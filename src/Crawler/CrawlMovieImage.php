<?php declare(strict_types=1);

namespace Learning\LearningBundle\Client;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\DomCrawler\Image;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;

class CrawlMovieImage extends AbstractApiClient
{
    private const URL = 'https://www.themoviedb.org/';

    public function crawl(): ?string
    {
        $name = $this->getName();
        $browser = new HttpBrowser(HttpClient::create());
        $crawler = $browser->request('GET', 'https://www.themoviedb.org/');

        $form = $crawler->filter('#inner_search_form')->form();

        $form['query'] = $name;

        $crawler = $browser->submit($form);

        $image = $crawler->selectImage($name)->image();

        $uri = 'https:' . $image->getNode()->getAttribute('data-src');

        if (!$uri) {
            throw new Exception('Url is invalid');
        }

        return $uri;
    }
}
