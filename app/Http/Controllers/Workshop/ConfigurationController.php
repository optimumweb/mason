<?php

namespace App\Http\Controllers\Workshop;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Command\Command;

class ConfigurationController extends Controller
{
    const ICON = 'fa-screwdriver-wrench';

    public function general(Request $request): Response
    {
        return response()->view('workshop.configuration.general', [
            'fields' => $this->getFields(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $configuration = $request->all()['configuration'] ?? [];

        $oldTheme = env('SITE_THEME');

        $env = [];

        foreach ($this->getFields() as $field) {
            $fieldName = $field['name'];

            if (array_key_exists($fieldName, $configuration)) {
                $value = $configuration[$fieldName];

                switch ($field['type']) {
                    case 'boolean':
                        $env[$fieldName] = isset($value) ? !! $value : null;
                        break;

                    default:
                        $env[$fieldName] = "{$value}";
                        break;
                }
            }
        }

        writeEnv($env);

        $newTheme = env('SITE_THEME');

        if ($newTheme !== $oldTheme) {
            Artisan::call('mason:theme:install');
        }

        return redirect()->route('workshop.configuration.general');
    }

    public function updateApp(Request $request): RedirectResponse
    {
        $result = Artisan::call('mason:update', ['--deploy' => true]);

        return redirect()->back()->with([
            'updateSuccess' => $success = ($result === Command::SUCCESS),
            'updateMessage' => $success
                ? __('configuration.general.actions.updateApp.success')
                : __('configuration.general.actions.updateApp.error'),
        ]);
    }

    public function updateTheme(Request $request): RedirectResponse
    {
        $result = Artisan::call('mason:theme:update');

        return redirect()->back()->with([
            'updateSuccess' => $success = ($result === Command::SUCCESS),
            'updateMessage' => $success
                ? __('configuration.general.actions.updateTheme.success')
                : __('configuration.general.actions.updateTheme.error'),
        ]);
    }

    protected function getFields(): array
    {
        return [
            [
                'name' => 'SITE_NAME',
                'label' => __('configuration.general.fields.siteName.label'),
                'type' => 'text',
                'required' => true,
                'value' => env('SITE_NAME'),
            ],

            [
                'name' => 'SITE_DESCRIPTION',
                'label' => __('configuration.general.fields.siteDescription.label'),
                'type' => 'text',
                'required' => true,
                'value' => env('SITE_DESCRIPTION'),
            ],

            [
                'name' => 'SITE_THEME',
                'label' => __('configuration.general.fields.siteTheme.label'),
                'type' => 'text',
                'required' => true,
                'value' => env('SITE_THEME'),
            ],

            [
                'name' => 'SITE_ALLOW_USER_REGISTRATION',
                'label' => __('configuration.general.fields.siteAllowUserRegistration.label'),
                'type' => 'boolean',
                'required' => true,
                'value' => env('SITE_ALLOW_USER_REGISTRATION'),
            ],

            [
                'name' => 'SITE_RESTRICT_USER_EMAIL_DOMAIN',
                'label' => __('configuration.general.fields.siteRestrictUserEmailDomain.label'),
                'type' => 'boolean',
                'required' => true,
                'value' => env('SITE_RESTRICT_USER_EMAIL_DOMAIN'),
            ],

            [
                'name' => 'SITE_ALLOWED_USER_EMAIL_DOMAINS',
                'label' => __('configuration.general.fields.siteAllowedUserEmailDomains.label'),
                'type' => 'text',
                'required' => false,
                'value' => env('SITE_ALLOWED_USER_EMAIL_DOMAINS'),
            ],

            [
                'name' => 'SENTRY_LARAVEL_DSN',
                'label' => __('configuration.general.fields.sentryDsn.label'),
                'help' => __('configuration.general.fields.sentryDsn.help'),
                'type' => 'text',
                'required' => false,
                'value' => env('SENTRY_LARAVEL_DSN'),
            ],
        ];
    }
}
