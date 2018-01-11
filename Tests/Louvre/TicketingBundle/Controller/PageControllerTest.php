<?php

namespace Tests\Louvre\TicketingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class PageControllerTest extends WebTestCase
{
    public function testHomepageIsUp()
    {
        $client = static::createClient();
        $client->request('GET','/');

        $this->assertSame(200, $client->getResponse()->getStatusCode());

        //echo $client->getResponse()->getContent();
    }

    public function testHomepage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET','/');

        $this->assertSame(1,$crawler->filter('html:contains("Bienvenue sur le site du Louvre")')->count());

    }

    public function testStepOne()
    {
        $client = static::createClient();
        $crawler = $client->request('GET','/');

        $link = $crawler->selectLink('Billetterie')->link();
        $crawler = $client->click($link);

        $form = $crawler->selectButton('Valider')->form();
        $form['louvre_ticketingbundle_booking[dateOfVisit]'] = '29-01-2018';
        $form['louvre_ticketingbundle_booking[duration]'] = 'journée';
        $form['louvre_ticketingbundle_booking[numberOfTickets]'] = 2;
        $form['louvre_ticketingbundle_booking[email]'] = 'fremendream@gmail.com';

        $client->submit($form);

        $crawler= $client->followRedirect();

        $this->assertSame(1,$crawler->filter('html:contains("lundi 29 janvier 2018")')->count());

    }

    public function testStepOneBankingDay()
    {
        $client = static::createClient();
        $crawler = $client->request('GET','/');

        $link = $crawler->selectLink('Billetterie')->link();
        $crawler = $client->click($link);

        $form = $crawler->selectButton('Valider')->form();
        $form['louvre_ticketingbundle_booking[dateOfVisit]'] = '10-05-2018';
        $form['louvre_ticketingbundle_booking[duration]'] = 'journée';
        $form['louvre_ticketingbundle_booking[numberOfTickets]'] = 2;
        $form['louvre_ticketingbundle_booking[email]'] = 'fremendream@gmail.com';

        $client->submit($form);

        $crawler= $client->reload();

        //echo $client->getResponse()->getContent();

        $this->assertSame(1,$crawler->filter('html:contains("Vous ne pouvez pas réserver de billets pour cette date !")')->count());

    }



    public function testStepTwo()
    {
        $client = static::createClient();
        $crawler = $client->request('GET','/');

        $link = $crawler->selectLink('Billetterie')->link();
        $crawler = $client->click($link);

        $form = $crawler->selectButton('Valider')->form();
        $form['louvre_ticketingbundle_booking[dateOfVisit]'] = '29-01-2018';
        $form['louvre_ticketingbundle_booking[duration]'] = 'journée';
        $form['louvre_ticketingbundle_booking[numberOfTickets]'] = 1;
        $form['louvre_ticketingbundle_booking[email]'] = 'fremendream@gmail.com';

        $client->submit($form);

        $crawler= $client->followRedirect();

        $form = $crawler->selectButton('Valider')->form();
        $form['louvre_ticketingbundle_bookingsteptwo[tickets][0][lastname]'] = 'Alves';
        $form['louvre_ticketingbundle_bookingsteptwo[tickets][0][firstname]'] = 'Frédéric';
        $form['louvre_ticketingbundle_bookingsteptwo[tickets][0][birthDate][day]'] = 22;
        $form['louvre_ticketingbundle_bookingsteptwo[tickets][0][birthDate][month]'] = 06;
        $form['louvre_ticketingbundle_bookingsteptwo[tickets][0][birthDate][year]'] = 1970;
        //$form['louvre_ticketingbundle_bookingsteptwo[tickets][0][category]'] = null;
        $form['louvre_ticketingbundle_bookingsteptwo[tickets][0][category]'] = 1;

        $client->submit($form);

        $crawler= $client->followRedirect();

        $this->assertSame(1,$crawler->filter('html:contains("il sera nécessaire que monsieur ou madame,")')->count());
    }
}