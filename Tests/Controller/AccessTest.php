<?php

namespace Application\PortfolioBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class AccessTest extends WebTestCase
{
    public function testAccessDeniedForUnathorizedUsers()
    {
        $this->_testReturnCode(401, $this->getUrl('blog_post_index', array()));
        $this->_testReturnCode(401, $this->getUrl('blog_post_create', array()));
        $this->_testReturnCode(401, $this->getUrl('blog_post_edit', array('slug' => 'my-first-post')));
        $this->_testReturnCode(401, $this->getUrl('blog_post_delete', array('slug' => 'my-first-post')));
    }

    public function testAccessAllowedForUnathorizedUsers()
    {
        $this->loadFixtures(array(
                'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadTagData',
                'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadPostData'));
        
        $this->_testReturnCode(200, $this->getUrl('blog', array()));
        $this->_testReturnCode(200, $this->getUrl('blog_post_view', array('slug' => 'my-first-post')), true);
    }

    /**
     * Проверяет код ответа
     *
     * @param string $url
     * @param int $code
     * @param bool $authentication
     */
    protected function _testReturnCode($code, $url, $authentication = false)
    {
        $client = $this->makeClient($authentication);
        $crawler = $client->request('GET', $url);

        $this->assertEquals($code, $client->getResponse()->getStatusCode());
    }
}