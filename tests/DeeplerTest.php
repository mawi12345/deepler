<?php

use PHPUnit\Framework\TestCase;

/**
 *  @author Martin Wind
 */
final class DeeplerTest extends TestCase {

    public function testEnvApiKey() {
        $authkey = getenv('DEEPL_API_KEY');
        $this->assertTrue(is_string($authkey), 'DEEPL_API_KEY environment variable is set');
    }

    public function testIsThereAnySyntaxError() {
        $api = new Mawi12345\Deepler\Deepler('key');
        $this->assertTrue(is_object($api));
    }

    public function testTranslate() {
        $api = new Mawi12345\Deepler\Deepler(getenv('DEEPL_API_KEY'));
        $translation = $api->translate('Hallo Welt', 'en', 'de');
        $this->assertEquals('Hello World', $translation);
    }

/*
    public function testLtiLaunchUrl() {
        $api = new BizQuiz\BizQuizAPI('demo', 'key');
        $this->assertEquals('https://bizquiz.cloud/api/lti', $api->getLTILaunchURL());
    }

    public function testGetLtiLaunchParameter() {
        $api = new BizQuiz\BizQuizAPI('demo', 'key');
        $parameters = $api->getLTILaunchParameter('12345');
        $this->assertArraySubset([
            'lti_message_type' => 'basic-lti-launch-request',
            'lti_version' => 'LTI-1p0',
            'user_id' => '12345',
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_version' => '1.0',
            'oauth_consumer_key' => 'demo',
            'oauth_callback' => 'about:blank',
        ], $parameters);
    }

    public function testLocalDashboardRequest() {
        $api = new BizQuiz\BizQuizAPI('demo', 'key', 'http://localhost:8000/api');
        $dashboard = $api->request('dashboard', ['userId' => 'extern']);
        print_r($dashboard);
    }
    */

}
