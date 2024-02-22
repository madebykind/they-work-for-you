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
        return Craft::$app->view->renderTemplate('_they-work-for-you/_settings.twig', [
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
                            'url' => 'settings/plugins/_they-work-for-you'
                        ]
                    ],
                ];
            }
        );

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['they-work-for-you/contacts'] = '_they-work-for-you/contact/index';
                $event->rules['they-work-for-you/contacts/import'] = ['template' => '_they-work-for-you/contacts/_import.twig'];
            }
        );

        // pretty urls
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['they-work-for-you/api/get-contact-details'] = '_they-work-for-you/api/get-contact-details';
                $event->rules['they-work-for-you/api/get-quota'] = '_they-work-for-you/api/get-quota';
                $event->rules['they-work-for-you/api/convert-url'] = '_they-work-for-you/api/convert-url';
                $event->rules['they-work-for-you/api/get-constituency'] = '_they-work-for-you/api/get-constituency';
                $event->rules['they-work-for-you/api/get-constituencies'] = '_they-work-for-you/api/get-constituencies';
                $event->rules['they-work-for-you/api/get-person'] = '_they-work-for-you/api/get-person';
                $event->rules['they-work-for-you/api/get-mp'] = '_they-work-for-you/api/get-mp';
                $event->rules['they-work-for-you/api/get-mp-info'] = '_they-work-for-you/api/get-mp-info';
                $event->rules['they-work-for-you/api/get-mps'] = '_they-work-for-you/api/get-mps';
                $event->rules['they-work-for-you/api/get-mps-info'] = '_they-work-for-you/api/get-mps-info';
                $event->rules['they-work-for-you/api/get-lord'] = '_they-work-for-you/api/get-lord';
                $event->rules['they-work-for-you/api/get-lords'] = '_they-work-for-you/api/get-lords';
                $event->rules['they-work-for-you/api/get-mla'] = '_they-work-for-you/api/get-mla';
                $event->rules['they-work-for-you/api/get-mlas'] = '_they-work-for-you/api/get-mlas';
                $event->rules['they-work-for-you/api/get-msp'] = '_they-work-for-you/api/get-msp';
                $event->rules['they-work-for-you/api/get-msps'] = '_they-work-for-you/api/get-msps';
                $event->rules['they-work-for-you/api/get-geometry'] = '_they-work-for-you/api/get-geometry';
                $event->rules['they-work-for-you/api/get-boundary'] = '_they-work-for-you/api/get-boundary';
                $event->rules['they-work-for-you/api/get-committee'] = '_they-work-for-you/api/get-committee';
                $event->rules['they-work-for-you/api/get-debates'] = '_they-work-for-you/api/get-debates';
                $event->rules['they-work-for-you/api/get-wrans'] = '_they-work-for-you/api/get-wrans';
                $event->rules['they-work-for-you/api/get-wms'] = '_they-work-for-you/api/get-wms';
                $event->rules['they-work-for-you/api/get-hansard'] = '_they-work-for-you/api/get-hansard';
                $event->rules['they-work-for-you/api/get-comments'] = '_they-work-for-you/api/get-comments';
            }
        );
    }
}
