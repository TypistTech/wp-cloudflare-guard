<?php

declare(strict_types=1);

namespace TypistTech\WPCFG\Ads;

use AspectMock\Test;
use Codeception\TestCase\WPTestCase;
use TypistTech\WPCFG\Admin;
use TypistTech\WPCFG\Vendor\TypistTech\WPContainedHook\Action;
use TypistTech\WPCFG\Vendor\Yoast_I18n_WordPressOrg_v2;

/**
 * @coversDefaultClass \TypistTech\WPCFG\Ads\I18nPromoter
 */
class I18nPromoterTest extends WPTestCase
{
    /**
     * @var \TypistTech\WPCFG\IntegrationTester
     */
    protected $tester;

    /**
     * @var I18nPromoter
     */
    private $i18nPromoter;

    public function setUp()
    {
        parent::setUp();

        $container = $this->tester->getContainer();

        $admin = Test::double(
            $container->get(Admin::class),
            [
                'getMenuSlugs' => [
                    'wpcfg-cloudflare',
                    'wpcfg-bad-login',
                ],
            ]
        );
        $container->share(Admin::class, $admin->getObject());

        $this->i18nPromoter = $container->get(I18nPromoter::class);
    }

    /**
     * @coversNothing
     */
    public function testGetFromContainer()
    {
        $this->assertInstanceOf(
            I18nPromoter::class,
            $this->tester->getContainer()->get(I18nPromoter::class)
        );
    }

    /**
     * @covers ::getHooks
     */
    public function testHookedIntoAdminMenu()
    {
        $actual = I18nPromoter::getHooks();

        $expected = [ new Action('admin_menu', I18nPromoter::class, 'run', 20) ];

        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers ::run
     */
    public function testYoastI18nWordPressOrgV2Initialized()
    {
        $yoastI18nWordPressOrgV2 = Test::double(Yoast_I18n_WordPressOrg_v2::class);

        $this->i18nPromoter->run();

        $yoastI18nWordPressOrgV2->verifyInvokedMultipleTimes('__construct', 2);
        $yoastI18nWordPressOrgV2->verifyInvokedOnce(
            '__construct',
            [
                [
                    'textdomain' => 'wp-cloudflare-guard',
                    'plugin_name' => 'WP Cloudflare Guard',
                    'hook' => 'wpcfg_cloudflare_after_option_form',
                ],
            ]
        );
        $yoastI18nWordPressOrgV2->verifyInvokedOnce(
            '__construct',
            [
                [
                    'textdomain' => 'wp-cloudflare-guard',
                    'plugin_name' => 'WP Cloudflare Guard',
                    'hook' => 'wpcfg_bad_login_after_option_form',
                ],
            ]
        );
    }
}
