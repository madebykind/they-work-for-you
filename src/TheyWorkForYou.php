<?php

namespace InLineStudio\TheyWorkForYou;

use Craft;
use InLineStudio\TheyWorkForYou\models\Settings;
use craft\base\Model;
use craft\base\Plugin;
use craft\events\RegisterCpNavItemsEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\web\UrlManager;
use craft\web\twig\variables\Cp;
use yii\base\Event;

/**
 * TheyWorkForYou plugin
 *
 * @method static TheyWorkForYou getInstance()
 * @method Settings getSettings()
 */
class TheyWorkForYou extends Plugin
{
    public $schemaVersion = '1.0.2';
    public $hasCpSettings = true;

    public static function config(): array
    {
        return [
            'components' => [
                // Define component configs here...
            ],
        ];
    }

    public function init(): void
    {
        parent::init();

        $this->attachEventHandlers();
    }

    protected function createSettingsModel(): ?Model
    {
        return Craft::createObject(Settings::class);
    }

    protected function settingsHtml(): ?string
    {
        return Craft::$app->view->renderTemplate('they-work-for-you/_settings.twig', [
            'plugin' => $this,
            'settings' => $this->getSettings(),
        ]);
    }

    private function attachEventHandlers(): void
    {
        // Register event handlers here ...
        // (see https://craftcms.com/docs/4.x/extend/events.html to get started)

        Event::on(
            Cp::class,
            Cp::EVENT_REGISTER_CP_NAV_ITEMS,
            function (RegisterCpNavItemsEvent $event) {
                $event->navItems[] = [
                    'url' => 'they-work-for-you/contacts',
                    'label' => 'They Work For You',
                    'icon' => '@app/icons/world.svg',
                    'subnav' => [
                        'mp-contact-info' => [
                            'label' => 'MP Contact Info',
                            'url' => 'they-work-for-you/contacts'
                        ],
                        'settings' => [
                            'label' => 'Settings',
                            'url' => 'settings/plugins/they-work-for-you'
                        ]
                    ],
                ];
            }
        );

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['they-work-for-you/contacts'] = 'they-work-for-you/contact/index';
                $event->rules['they-work-for-you/contacts/import'] = ['template' => 'they-work-for-you/contacts/_import.twig'];
            }
        );

        // pretty urls
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['they-work-for-you/api/get-contact-details'] = 'they-work-for-you/api/get-contact-details';
                $event->rules['they-work-for-you/api/get-quota'] = 'they-work-for-you/api/get-quota';
                $event->rules['they-work-for-you/api/convert-url'] = 'they-work-for-you/api/convert-url';
                $event->rules['they-work-for-you/api/get-constituency'] = 'they-work-for-you/api/get-constituency';
                $event->rules['they-work-for-you/api/get-constituencies'] = 'they-work-for-you/api/get-constituencies';
                $event->rules['they-work-for-you/api/get-person'] = 'they-work-for-you/api/get-person';
                $event->rules['they-work-for-you/api/get-mp'] = 'they-work-for-you/api/get-mp';
                $event->rules['they-work-for-you/api/get-mp-info'] = 'they-work-for-you/api/get-mp-info';
                $event->rules['they-work-for-you/api/get-mps'] = 'they-work-for-you/api/get-mps';
                $event->rules['they-work-for-you/api/get-mps-info'] = 'they-work-for-you/api/get-mps-info';
                $event->rules['they-work-for-you/api/get-lord'] = 'they-work-for-you/api/get-lord';
                $event->rules['they-work-for-you/api/get-lords'] = 'they-work-for-you/api/get-lords';
                $event->rules['they-work-for-you/api/get-mla'] = 'they-work-for-you/api/get-mla';
                $event->rules['they-work-for-you/api/get-mlas'] = 'they-work-for-you/api/get-mlas';
                $event->rules['they-work-for-you/api/get-msp'] = 'they-work-for-you/api/get-msp';
                $event->rules['they-work-for-you/api/get-msps'] = 'they-work-for-you/api/get-msps';
                $event->rules['they-work-for-you/api/get-geometry'] = 'they-work-for-you/api/get-geometry';
                $event->rules['they-work-for-you/api/get-boundary'] = 'they-work-for-you/api/get-boundary';
                $event->rules['they-work-for-you/api/get-committee'] = 'they-work-for-you/api/get-committee';
                $event->rules['they-work-for-you/api/get-debates'] = 'they-work-for-you/api/get-debates';
                $event->rules['they-work-for-you/api/get-wrans'] = 'they-work-for-you/api/get-wrans';
                $event->rules['they-work-for-you/api/get-wms'] = 'they-work-for-you/api/get-wms';
                $event->rules['they-work-for-you/api/get-hansard'] = 'they-work-for-you/api/get-hansard';
                $event->rules['they-work-for-you/api/get-comments'] = 'they-work-for-you/api/get-comments';
            }
        );
    }
}
