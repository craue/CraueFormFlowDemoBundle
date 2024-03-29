<?php

namespace Craue\FormFlowDemoBundle\Tests;

use Craue\FormFlowDemoBundle\Entity\Topic;

/**
 * @group integration
 *
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2022 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class CreateTopicFlowTest extends IntegrationTestCase {

	public function testCreateTopic() {
		static::$client->followRedirects();
		$crawler = static::$client->request('GET', $this->url('_FormFlow_createTopic'));

		$this->assertSame(200, static::$client->getResponse()->getStatusCode());
		$this->assertCurrentStepNumber(1, $crawler);

		$form = $crawler->selectButton('next')->form();

		$this->assertEquals('', $form->get('createTopic[title]')->getValue());
		$this->assertEquals('', $form->get('createTopic[description]')->getValue());
		$this->assertEquals('', $form->get('createTopic[category]')->getValue());

		$expectedOptionsCategory = [
			[
				'value' => '',
				'label' => '',
			]
		];
		$validCategories = Topic::getValidCategories();
		sort($validCategories);
		foreach ($validCategories as $category) {
			$expectedOptionsCategory[] = [
				'value' => $category,
				'label' => $this->trans($category, [], 'topicCategories'),
			];
		}
		$this->assertEquals($expectedOptionsCategory, $this->getOptionsOfSelectField('#createTopic_category', $crawler));

		// empty fields -> step 1 again
		$crawler = static::$client->submit($form, [
			'createTopic[title]' => '',
			'createTopic[category]' => '',
		]);
		$this->assertCurrentStepNumber(1, $crawler);
		$this->assertFieldHasError('#createTopic_title', 'This value should not be blank.', $crawler);
		$this->assertFieldHasError('#createTopic_category', 'This value should not be blank.', $crawler);

		// invalid category -> step 1 again
		$form = $crawler->selectButton('next')->form();
		$form->disableValidation();
		$crawler = static::$client->submit($form, [
			'createTopic[category]' => 'invalid',
		]);
		$this->assertCurrentStepNumber(1, $crawler);
		try {
			$this->assertFieldHasError('#createTopic_category', 'The selected choice is invalid.', $crawler);
		} catch (\PHPUnit\Framework\ExpectationFailedException $e) {
			// TODO remove as soon as Symfony >= 6.0 is required
			$this->assertFieldHasError('#createTopic_category', 'This value is not valid.', $crawler);
		}

		// bug report -> step 2
		$crawler = static::$client->submit($form, [
			'createTopic[title]' => 'blah',
			'createTopic[category]' => 'BUG_REPORT',
		]);
		$this->assertCurrentStepNumber(2, $crawler);

		// next -> step 3
		$form = $crawler->selectButton('next')->form();
		$crawler = static::$client->submit($form, [
			'createTopic[comment]' => '',
		]);
		$this->assertCurrentStepNumber(3, $crawler);

		// empty field -> step 3 again
		$form = $crawler->selectButton('next')->form();
		$crawler = static::$client->submit($form, [
			'createTopic[details]' => '',
		]);
		$this->assertCurrentStepNumber(3, $crawler);
		$this->assertFieldHasError('#createTopic_details', 'This value should not be blank.', $crawler);

		// bug details -> step 4
		$form = $crawler->selectButton('next')->form();
		$crawler = static::$client->submit($form, [
			'createTopic[details]' => 'blah blah',
		]);
		$this->assertCurrentStepNumber(4, $crawler);

		// finish flow
		$form = $crawler->selectButton('finish')->form();
		static::$client->submit($form);
		$this->assertEquals('_FormFlow_start', static::$client->getRequest()->attributes->get('_route'));
	}

	public function testCreateTopic_dynamicStepNavigation() {
		static::$client->followRedirects();
		$crawler = static::$client->request('GET', $this->url('_FormFlow_createTopic_dynamicStepNavigation'));

		$this->assertSame(200, static::$client->getResponse()->getStatusCode());
		$this->assertCurrentStepNumber(1, $crawler);
		$this->assertCount(0, $crawler->filter('.flow-steps a'));

		// bug report -> step 2
		$form = $crawler->selectButton('next')->form();
		$crawler = static::$client->submit($form, [
			'createTopic[title]' => 'blah',
			'createTopic[category]' => 'BUG_REPORT',
		]);
		$this->assertCurrentStepNumber(2, $crawler);
		$this->assertCount(1, $crawler->filter('.flow-steps a'));

		// comment -> step 3
		$form = $crawler->selectButton('next')->form();
		$crawler = static::$client->submit($form, [
			'createTopic[comment]' => 'my comment',
		]);
		$this->assertCurrentStepNumber(3, $crawler);
		$this->assertCount(2, $crawler->filter('.flow-steps a'));

		// bug details -> step 4
		$form = $crawler->selectButton('next')->form();
		$crawler = static::$client->submit($form, [
			'createTopic[details]' => 'blah blah',
		]);
		$this->assertCurrentStepNumber(4, $crawler);
		$this->assertCount(3, $crawler->filter('.flow-steps a'));

		$this->assertEquals([
			'title' => 'blah',
			'description' => html_entity_decode('&mdash;'),
			'category' => 'bug report',
			'comment' => 'my comment',
			'details' => 'blah blah',
		], $this->getListContent('', $crawler));

		// back to step 1 via DSN
		$linkToStep1 = $crawler->filter('.flow-steps a')->selectLink('basics')->link()->getUri();
		$crawler = static::$client->request('GET', $linkToStep1);
		$this->assertCurrentStepNumber(1, $crawler);
		$this->assertCount(3, $crawler->filter('.flow-steps a'));

		$form = $crawler->selectButton('next')->form();

		$this->assertEquals('blah', $form->get('createTopic[title]')->getValue());
		$this->assertEquals('BUG_REPORT', $form->get('createTopic[category]')->getValue());

		// discussion -> step 2
		$crawler = static::$client->submit($form, [
			'createTopic[title]' => 'blahhh',
			'createTopic[category]' => 'DISCUSSION',
		]);
		$this->assertCurrentStepNumber(2, $crawler);
		$this->assertCount(2, $crawler->filter('.flow-steps a')); // link the last step as it's been visited already

		$form = $crawler->selectButton('next')->form();

		$this->assertEquals('my comment', $form->get('createTopic[comment]')->getValue());

		// keep as is -> step 4
		$crawler = static::$client->submit($form);
		$this->assertCurrentStepNumber(4, $crawler);
		$this->assertCount(2, $crawler->filter('.flow-steps a'));

		$this->assertEquals([
			'title' => 'blahhh',
			'description' => html_entity_decode('&mdash;'),
			'category' => 'discussion',
			'comment' => 'my comment',
		], $this->getListContent('', $crawler));

		// finish flow
		$form = $crawler->selectButton('finish')->form();
		static::$client->submit($form);
		$this->assertEquals('_FormFlow_start', static::$client->getRequest()->attributes->get('_route'));
	}

	public function testCreateTopic_redirectAfterSubmit() {
		static::$client->followRedirects();
		$crawler = static::$client->request('GET', $this->url('_FormFlow_createTopic_redirectAfterSubmit'));

		$this->assertSame(200, static::$client->getResponse()->getStatusCode());
		$this->assertCurrentStepNumber(1, $crawler);

		// reset -> step 1
		$form = $crawler->selectButton('start over')->form();
		$crawler = static::$client->submit($form);
		$this->assertCurrentStepNumber(1, $crawler);
		$this->assertEquals('GET', static::$client->getRequest()->getMethod());

		// bug report -> step 2
		$form = $crawler->selectButton('next')->form();
		$crawler = static::$client->submit($form, [
			'createTopic[title]' => 'blah',
			'createTopic[category]' => 'BUG_REPORT',
		]);
		$this->assertCurrentStepNumber(2, $crawler);
		$this->assertEquals('GET', static::$client->getRequest()->getMethod());

		// comment -> step 3
		$form = $crawler->selectButton('next')->form();
		$crawler = static::$client->submit($form, [
			'createTopic[comment]' => 'my comment',
		]);
		$this->assertCurrentStepNumber(3, $crawler);
		$this->assertEquals('GET', static::$client->getRequest()->getMethod());

		// bug details -> step 4
		$form = $crawler->selectButton('next')->form();
		$crawler = static::$client->submit($form, [
			'createTopic[details]' => 'blah blah',
		]);
		$this->assertCurrentStepNumber(4, $crawler);
		$this->assertEquals('GET', static::$client->getRequest()->getMethod());

		$this->assertEquals([
			'title' => 'blah',
			'description' => html_entity_decode('&mdash;'),
			'category' => 'bug report',
			'comment' => 'my comment',
			'details' => 'blah blah',
		], $this->getListContent('', $crawler));

		// go back -> step 3
		$form = $crawler->selectButton('back')->form();
		$crawler = static::$client->submit($form);
		$this->assertCurrentStepNumber(3, $crawler);
		$this->assertEquals('GET', static::$client->getRequest()->getMethod());

		$form = $crawler->selectButton('next')->form();

		$this->assertEquals('blah blah', $form->get('createTopic[details]')->getValue());

		// bug details -> step 4
		$crawler = static::$client->submit($form);
		$this->assertCurrentStepNumber(4, $crawler);
		$this->assertEquals('GET', static::$client->getRequest()->getMethod());

		$this->assertEquals([
			'title' => 'blah',
			'description' => html_entity_decode('&mdash;'),
			'category' => 'bug report',
			'comment' => 'my comment',
			'details' => 'blah blah',
		], $this->getListContent('', $crawler));

		// finish flow
		$form = $crawler->selectButton('finish')->form();
		static::$client->submit($form);
		$this->assertEquals('_FormFlow_start', static::$client->getRequest()->attributes->get('_route'));
	}

}
