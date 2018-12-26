<?php

namespace Craue\FormFlowDemoBundle\Tests;

/**
 * @group integration
 *
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2018 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class PhotoUploadFlowTest extends IntegrationTestCase {

	const DOCUMENT = '/Fixtures/some-text.txt';
	const IMAGE = '/Fixtures/blue-pixel.png';

	public function testPhotoUpload() {
		$document = __DIR__ . self::DOCUMENT;
		$image = __DIR__ . self::IMAGE;

		$this->client->followRedirects();
		$crawler = $this->client->request('GET', $this->url('_FormFlow_photoUpload'));

		$this->assertSame(200, $this->client->getResponse()->getStatusCode());
		$this->assertCurrentStepNumber(1, $crawler);

		// don't upload any file -> step 1
		$form = $crawler->selectButton('next')->form();
		$crawler = $this->client->submit($form);
		$this->assertCurrentStepNumber(1, $crawler);
		$this->assertFieldHasError('#photoUpload_photo', 'This value should not be null.', $crawler);

		// try uploading non-image file -> step 1
		$form = $crawler->selectButton('next')->form();
		$form['photoUpload[photo]']->upload($document);
		$crawler = $this->client->submit($form);
		$this->assertCurrentStepNumber(1, $crawler);
		$this->assertFieldHasError('#photoUpload_photo', 'This file is not a valid image.', $crawler);

		// upload the photo -> step 2
		$form = $crawler->selectButton('next')->form();
		$form['photoUpload[photo]']->upload($image);
		$crawler = $this->client->submit($form);
		$this->assertCurrentStepNumber(2, $crawler);

		// comment -> step 3
		$form = $crawler->selectButton('next')->form();
		$crawler = $this->client->submit($form, [
			'photoUpload[comment]' => 'blah',
		]);
		$this->assertCurrentStepNumber(3, $crawler);

		$this->assertEquals([
			'photo' => '',
			'comment' => 'blah',
		], $this->getListContent('', $crawler));
		$this->assertImageSrc('img', sprintf('data:image/png;base64,%s', base64_encode(file_get_contents($image))), $crawler);

		// finish flow
		$form = $crawler->selectButton('finish')->form();
		$this->client->submit($form);
		$this->assertEquals('_FormFlow_start', $this->client->getRequest()->attributes->get('_route'));
	}

}
