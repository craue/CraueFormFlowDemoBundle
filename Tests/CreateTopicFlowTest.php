<?php

namespace Craue\FormFlowDemoBundle\Tests;

use Craue\FormFlowDemoBundle\Entity\Topic;

/**
 * @group integration
 *
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2017 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class CreateTopicFlowTest extends IntegrationTestCase {

	public function testCreateTopic() {
		$this->client->followRedirects();
		$crawler = $this->client->request('GET', $this->url('_FormFlow_createTopic'));

		$this->assertSame(200, $this->client->getResponse()->getStatusCode());
		$this->assertCurrentStepNumber(1, $crawler);

		$form = $crawler->selectButton('next')->form();

		$this->assertEquals('', $form->get('createTopic[title]')->getValue());
		$this->assertEquals('', $form->get('createTopic[description]')->getValue());
		$this->assertEquals('', $form->get('createTopic[category]')->getValue());

		$expectedOptionsCategory = array(
			array(
				'value' => '',
				'label' => '',
			)
		);
		$validCategories = Topic::getValidCategories();
		sort($validCategories);
		foreach ($validCategories as $category) {
			$expectedOptionsCategory[] = array(
				'value' => $category,
				'label' => $this->trans($category, array(), 'topicCategories'),
			);
		}
		$this->assertEquals($expectedOptionsCategory, $this->getOptionsOfSelectField('#createTopic_category', $crawler));

		// empty fields -> step 1 again
		$crawler = $this->client->submit($form, array(
			'createTopic[title]' => '',
			'createTopic[category]' => '',
		));
		$this->assertCurrentStepNumber(1, $crawler);
		$this->assertFieldHasError('#createTopic_title', 'This value should not be blank.', $crawler);
		$this->assertFieldHasError('#createTopic_category', 'This value should not be blank.', $crawler);

		// invalid category -> step 1 again
		$form = $crawler->selectButton('next')->form();
		if (method_exists($form, 'disableValidation')) {
			$form->disableValidation();
			$crawler = $this->client->submit($form, array(
				'createTopic[category]' => 'invalid',
			));
		} else {
			// impossible to send invalid values with DomCrawler, see https://github.com/symfony/symfony/issues/7672
			// TODO remove as soon as Symfony >= 2.4 is required
			$crawler = $this->client->request($form->getMethod(), $form->getUri(), array(
				'flow_createVehicle_step' => 1,
				'createTopic' => array(
					'category' => 'invalid',
				),
			));
		}
		$this->assertCurrentStepNumber(1, $crawler);
		$this->assertFieldHasError('#createTopic_category', 'This value is not valid.', $crawler);

		// bug report -> step 2
		$crawler = $this->client->submit($form, array(
			'createTopic[title]' => 'blah',
			'createTopic[category]' => 'BUG_REPORT',
		));
		$this->assertCurrentStepNumber(2, $crawler);

		// next -> step 3
		$form = $crawler->selectButton('next')->form();
		$crawler = $this->client->submit($form, array(
			'createTopic[comment]' => '',
		));
		$this->assertCurrentStepNumber(3, $crawler);

		// empty field -> step 3 again
		$form = $crawler->selectButton('next')->form();
		$crawler = $this->client->submit($form, array(
			'createTopic[details]' => '',
		));
		$this->assertCurrentStepNumber(3, $crawler);
		$this->assertFieldHasError('#createTopic_details', 'This value should not be blank.', $crawler);

		// bug details -> step 4
		$form = $crawler->selectButton('next')->form();
		$crawler = $this->client->submit($form, array(
			'createTopic[details]' => 'blah blah',
		));
		$this->assertCurrentStepNumber(4, $crawler);

		// finish flow
		$form = $crawler->selectButton('finish')->form();
		$this->client->submit($form);
		$this->assertEquals('_FormFlow_start', $this->client->getRequest()->attributes->get('_route'));
	}

	public function testCreateTopic_dynamicStepNavigation() {
		$this->client->followRedirects();
		$crawler = $this->client->request('GET', $this->url('_FormFlow_createTopic_dynamicStepNavigation_start'));

		$this->assertSame(200, $this->client->getResponse()->getStatusCode());
		$this->assertCurrentStepNumber(1, $crawler);
		$this->assertCount(0, $crawler->filter('.flow-steps a'));

		// bug report -> step 2
		$form = $crawler->selectButton('next')->form();
		$crawler = $this->client->submit($form, array(
			'createTopic[title]' => 'blah',
			'createTopic[category]' => 'BUG_REPORT',
		));
		$this->assertCurrentStepNumber(2, $crawler);
		$this->assertCount(1, $crawler->filter('.flow-steps a'));

		// comment -> step 3
		$form = $crawler->selectButton('next')->form();
		$crawler = $this->client->submit($form, array(
			'createTopic[comment]' => 'my comment',
		));
		$this->assertCurrentStepNumber(3, $crawler);
		$this->assertCount(2, $crawler->filter('.flow-steps a'));

		// bug details -> step 4
		$form = $crawler->selectButton('next')->form();
		$crawler = $this->client->submit($form, array(
			'createTopic[details]' => 'blah blah',
		));
		$this->assertCurrentStepNumber(4, $crawler);
		$this->assertCount(3, $crawler->filter('.flow-steps a'));

		$this->assertEquals(array(
			'title' => 'blah',
			'description' => html_entity_decode('&mdash;', null, 'UTF-8'), // TODO remove last two arguments as soon as PHP >= 5.4 is required
			'category' => 'bug report',
			'comment' => 'my comment',
			'details' => 'blah blah',
		), $this->getListContent('', $crawler));

		// back to step 1 via DSN
		$linkToStep1 = $crawler->filter('.flow-steps a')->selectLink('basics')->link()->getUri();
		$crawler = $this->client->request('GET', $linkToStep1);
		$this->assertCurrentStepNumber(1, $crawler);
		$this->assertCount(3, $crawler->filter('.flow-steps a'));

		$form = $crawler->selectButton('next')->form();

		$this->assertEquals('blah', $form->get('createTopic[title]')->getValue());
		$this->assertEquals('BUG_REPORT', $form->get('createTopic[category]')->getValue());

		// discussion -> step 2
		$crawler = $this->client->submit($form, array(
			'createTopic[title]' => 'blahhh',
			'createTopic[category]' => 'DISCUSSION',
		));
		$this->assertCurrentStepNumber(2, $crawler);
		$this->assertCount(2, $crawler->filter('.flow-steps a')); // link the last step as it's been visited already

		$form = $crawler->selectButton('next')->form();

		$this->assertEquals('my comment', $form->get('createTopic[comment]')->getValue());

		// keep as is -> step 4
		$crawler = $this->client->submit($form);
		$this->assertCurrentStepNumber(4, $crawler);
		$this->assertCount(2, $crawler->filter('.flow-steps a'));

		$this->assertEquals(array(
			'title' => 'blahhh',
			'description' => html_entity_decode('&mdash;', null, 'UTF-8'), // TODO remove last two arguments as soon as PHP >= 5.4 is required
			'category' => 'discussion',
			'comment' => 'my comment',
		), $this->getListContent('', $crawler));

		// finish flow
		$form = $crawler->selectButton('finish')->form();
		$this->client->submit($form);
		$this->assertEquals('_FormFlow_start', $this->client->getRequest()->attributes->get('_route'));
	}

}
