<?php

namespace Stfalcon\Bundle\BlogBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

abstract class AbstractTestCase extends WebTestCase
{
    public function paginationCheck($crawled_url, $text)
    {
        $client = static::createClient();
        $this->loadFixtures(array(
                'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadTagData',
                'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadPostPaginatorData'));
        $crawler = $this->fetchCrawler($this->getUrl($crawled_url, array('text' => $text, 'page' => '1')), 'GET', true, true);

        $this->assertEquals(1, $crawler->filter('.pagination .current:contains("1")')->count());

        $this->assertEquals(10, $crawler->filter('.post')->count());

        $this->assertEquals(5, $crawler->filter('.pagination span')->count());

        $crawler = $this->fetchCrawler($this->getUrl($crawled_url, array('text' => $text, 'page' => '2')), 'GET', true, true);

        $this->assertEquals(1, $crawler->filter('.pagination .current:contains("2")')->count());

        $this->assertEquals(7, $crawler->filter('.pagination span')->count());

        $this->assertEquals(10, $crawler->filter('.post')->count());

        // @todo refact start
        $link5 = $crawler->filter('span.next a')->link();
        $crawler = $client->click($link5);
        $this->assertEquals(3, $client->getRequest()->attributes->get('page'));

        $link2 = $crawler->selectLink('1')->link();
        $crawler = $client->click($link2);
        $this->assertEquals(1, $client->getRequest()->attributes->get('page'));

        $crawler = $this->fetchCrawler(
                $this->getUrl('blog', array('page'=> 3)), 'GET', true, true
        );
        $this->assertEquals(1, $crawler->filter('.pagination .current:contains("3")')->count());

        $this->assertEquals(5, $crawler->filter('.pagination span')->count());

        $this->assertEquals(10, $crawler->filter('.post')->count());
        $link3 = $crawler->selectLink('2')->link();
        $crawler = $client->click($link3);
        $this->assertEquals(2, $client->getRequest()->attributes->get('page'));

        $link6 = $crawler->filter('span.previous a')->link();
        $crawler = $client->click($link6);
        $this->assertEquals(1, $client->getRequest()->attributes->get('page'));

        $link7 = $crawler->filter('span.last a')->link();
        $crawler = $client->click($link7);
        $this->assertEquals(3, $client->getRequest()->attributes->get('page'));

        $link8 = $crawler->filter('span.first a')->link();
        $crawler = $client->click($link8);
        $this->assertEquals(1, $client->getRequest()->attributes->get('page'));

        $crawler = $this->fetchCrawler(
                $this->getUrl('blog', array('page'=> 1)), 'GET', true, true
        );
        $this->assertEquals(0, $crawler->filter('span.first a')->count());
        $this->assertEquals(0, $crawler->filter('span.previous a')->count());

        $crawler = $this->fetchCrawler($this->getUrl($crawled_url, array('text' => $text, 'page' => '2')), 'GET', true, true);

        $this->assertEquals(1, $crawler->filter('.pagination .current:contains("2")')->count());

        $this->assertEquals(7, $crawler->filter('.pagination span')->count());

        $this->assertEquals(10, $crawler->filter('.post')->count());

        // @todo refact start
        $link5 = $crawler->filter('span.next a')->link();
        $crawler = $client->click($link5);
        $this->assertEquals(3, $client->getRequest()->attributes->get('page'));

        $link2 = $crawler->selectLink('1')->link();
        $crawler = $client->click($link2);
        $this->assertEquals(1, $client->getRequest()->attributes->get('page'));

        $crawler = $this->fetchCrawler(
                $this->getUrl('blog', array('page'=> 3)), 'GET', true, true
        );
        $this->assertEquals(1, $crawler->filter('.pagination .current:contains("3")')->count());

        $this->assertEquals(5, $crawler->filter('.pagination span')->count());

        $this->assertEquals(10, $crawler->filter('.post')->count());
        $link3 = $crawler->selectLink('2')->link();
        $crawler = $client->click($link3);
        $this->assertEquals(2, $client->getRequest()->attributes->get('page'));

        $link6 = $crawler->filter('span.previous a')->link();
        $crawler = $client->click($link6);
        $this->assertEquals(1, $client->getRequest()->attributes->get('page'));

        $link7 = $crawler->filter('span.last a')->link();
        $crawler = $client->click($link7);
        $this->assertEquals(3, $client->getRequest()->attributes->get('page'));

        $link8 = $crawler->filter('span.first a')->link();
        $crawler = $client->click($link8);
        $this->assertEquals(1, $client->getRequest()->attributes->get('page'));

        $crawler = $this->fetchCrawler($this->getUrl($crawled_url, array('text' => $text, 'page' => '1')), 'GET', true, true);
        $this->assertEquals(0, $crawler->filter('span.first a')->count());
        $this->assertEquals(0, $crawler->filter('span.previous a')->count());

        $crawler = $this->fetchCrawler(
                $this->getUrl('blog', array('page'=> 3)), 'GET', true, true
        );

        $this->assertEquals(0, $crawler->filter('span.last a')->count());
        $this->assertEquals(0, $crawler->filter('span.next a')->count());
    }
}
