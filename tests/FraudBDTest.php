<?php

namespace Mainul12501\Laravel\Tests;

use Mainul12501\Laravel\FraudBD;
use Mainul12501\Laravel\Exceptions\InvalidConfigException;
use Orchestra\Testbench\TestCase;
use Mainul12501\Laravel\FraudBDServiceProvider;

class FraudBDTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [FraudBDServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('fraudbd.api_key', 'test-api-key');
        $app['config']->set('fraudbd.username', 'test-username');
        $app['config']->set('fraudbd.password', 'test-password');
        $app['config']->set('fraudbd.base_url', 'https://fraudbd.com');
    }

    /** @test */
    public function it_can_be_instantiated()
    {
        $fraudbd = new FraudBD([
            'api_key' => 'test-key',
            'username' => 'test-user',
            'password' => 'test-pass',
            'base_url' => 'https://fraudbd.com',
        ]);

        $this->assertInstanceOf(FraudBD::class, $fraudbd);
    }

    /** @test */
    public function it_throws_exception_when_api_key_is_missing()
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('FraudBD API key is not configured');

        new FraudBD([
            'api_key' => '',
            'username' => 'test-user',
            'password' => 'test-pass',
        ]);
    }

    /** @test */
    public function it_throws_exception_when_username_is_missing()
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('FraudBD username is not configured');

        new FraudBD([
            'api_key' => 'test-key',
            'username' => '',
            'password' => 'test-pass',
        ]);
    }

    /** @test */
    public function it_throws_exception_when_password_is_missing()
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('FraudBD password is not configured');

        new FraudBD([
            'api_key' => 'test-key',
            'username' => 'test-user',
            'password' => '',
        ]);
    }

    /** @test */
    public function it_can_resolve_from_container()
    {
        $fraudbd = $this->app->make('fraudbd');

        $this->assertInstanceOf(FraudBD::class, $fraudbd);
    }

    /** @test */
    public function it_can_set_api_key()
    {
        $fraudbd = $this->app->make('fraudbd');
        $result = $fraudbd->setApiKey('new-api-key');

        $this->assertInstanceOf(FraudBD::class, $result);
    }

    /** @test */
    public function it_can_set_credentials()
    {
        $fraudbd = $this->app->make('fraudbd');
        $result = $fraudbd->setCredentials('new-user', 'new-pass');

        $this->assertInstanceOf(FraudBD::class, $result);
    }

    /** @test */
    public function it_can_set_base_url()
    {
        $fraudbd = $this->app->make('fraudbd');
        $result = $fraudbd->setBaseUrl('https://custom.fraudbd.com');

        $this->assertInstanceOf(FraudBD::class, $result);
    }
}
