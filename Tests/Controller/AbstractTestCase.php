<?php

namespace Stfalcon\Bundle\BlogBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Pagination test cases for PostController and TagController
 *
 * @author Alexandr Yaremyuk <sirmutuh@gmail.com>
 */
abstract class AbstractTestCase extends WebTestCase
{
 /**
 * Abstract pagination test method for both post an
 *
 */
    protected function paginationCheck($crawled_url, $text)
    {
        $client = static::createClient();
        
        $this->loadFixtures(array(
                'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadTagData',
                'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadPostPaginatorData'));

        $crawler = $this->fetchCrawler(
                $this->getUrl($crawled_url, array('text' => $text, 'page' => '1')), 'GET', true, true
        );

        $this->assertEquals(1, $crawler->filter('.pagination .current:contains("1")')->count());
        $this->assertEquals(10, $crawler->filter('.post')->count());
        $this->assertEquals(5, $crawler->filter('.pagination span')->count());
        $this->assertEquals(0, $crawler->filter('span.first a')->count());
        $this->assertEquals(0, $crawler->filter('span.previous a')->count());


        $crawler = $this->fetchCrawler(
                $this->getUrl($crawled_url, array('text' => $text, 'page' => '2')), 'GET', true, true
        );

        $this->assertEquals(1, $crawler->filter('.pagination .current:contains("2")')->count());
        $this->assertEquals(7, $crawler->filter('.pagination span')->count());
        $this->isLinkClickable($client, $crawler, 'next', 3);
        $this->isLinkClickable($client, $crawler, 'previous', 1);
        $this->isLinkClickable($client, $crawler, 'last', 3);
        $this->isLinkClickable($client, $crawler, 'first', 1);
        $this->isLinkClickableByNumber($client, $crawler, 1, 1);


        $crawler = $this->fetchCrawler(
                $this->getUrl('blog', array('page'=> 3)), 'GET', true, true
        );

        $this->assertEquals(1, $crawler->filter('.pagination .current:contains("3")')->count());
        $this->isLinkClickableByNumber($client, $crawler, 2, 2);
        $this->assertEquals(10, $crawler->filter('.post')->count());
        $this->assertEquals(0, $crawler->filter('span.last a')->count());
        $this->assertEquals(0, $crawler->filter('span.next a')->count());


    }

   private function isLinkClickable($client, $crawler, $linkname, $expectedInt)
   {
       $link = $crawler->filter('span.'.$linkname.' a')->link();
       $crawler = $client->click($link);
       $this->assertEquals($expectedInt, $client->getRequest()->attributes->get('page'));
   }

   private function isLinkClickableByNumber($client, $crawler, $number, $expectedInt)
   {
       $link = $crawler->selectLink(''.$number.'')->link();
       $crawler = $client->click($link);
       $this->assertEquals($expectedInt, $client->getRequest()->attributes->get('page'));
   }
}
