<?php

namespace AppBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class CategoryControllerTest extends WebTestCase
{
    protected $client;

    protected function setUp() {
        $this->loadFixtures([
            'AppBundle\DataFixtures\ORM\LoadCategoryData',
            'AppBundle\DataFixtures\ORM\LoadClientData',
            'AppBundle\DataFixtures\ORM\LoadProductData',
        ]);
        $this->client = static::createClient();
    }

    public function testIndex() {
        $crawler = $this->client->request('GET', $this->getUrl('category_index'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $link = $crawler->filter('a#new-category-link')->link();
        $crawler = $this->client->click($link);
        
        $this->assertEquals('category_new', $this->getCurrentRoute());
    }

    public function testNew() {
        $crawler = $this->client->request('GET', $this->getUrl('category_new'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $form = $crawler->filter('input#create-category-button')->form([
            'appbundle_category[designation]' => 'Ma catégorie de test'
        ]);

        $this->client->submit($form);

        $crawler = $this->client->followRedirect();

        $this->assertEquals('category_show', $this->getCurrentRoute());
    }

    public function testEdit() {
        $crawler = $this->client->request('GET', $this->getUrl('category_edit', ['id' => 1]));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $form = $crawler->filter('input#edit-category-button')->form([
            'appbundle_category[designation]' => 'Jeux vidéo & Console'
        ]);

        $this->client->submit($form);

        $crawler = $this->client->followRedirect();

        $this->assertEquals('category_edit', $this->getCurrentRoute());

        $inputValue = $crawler->filter('#appbundle_category_designation')->attr('value');

        $this->assertEquals($inputValue, 'Jeux vidéo & Console');
    }

    public function testDelete() {
        $crawler = $this->client->request('DELETE', $this->getUrl('category_delete', ['id' => 1]));
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();

        $this->assertEquals('category_index', $this->getCurrentRoute());
    }

    private function getCurrentRoute() {
        return $this->client->getRequest()->get('_route');
    }
}
