<?php

namespace App\Providers;


use Outl1ne\NovaSettings\NovaSettings;
use Eminiarts\Tabs\Tabs;

use App\Models\{Admin as AdminModel, Contact  as ContactModel, User as UserModel};

use App\Nova\{
    Dashboards\Main, Admin, User, Contact, FrequentAskedQuestion, Partners,
    Policy,
    TermCondition
};

use Laravel\Nova\{
    Nova,
    Fields\Text,
    Fields\Image,
    Fields\Textarea,
    Fields\URL,
    Menu\MenuSection,
    Menu\MenuItem,
    Panel,
    NovaApplicationServiceProvider
};

use Illuminate\{Http\Request as HttpRequest, Support\Facades\Gate};

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        parent::boot();

        $this->customMenu();

        $this->NovaSettings();
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes()
            ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return in_array($user->email, AdminModel::all()->pluck('email')->toArray());
        });
    }

    /**
     * Get the dashboards that should be listed in the Nova sidebar.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [
            Main::make()->showRefreshButton()
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [
            new NovaSettings,
            new \Badinansoft\LanguageSwitch\LanguageSwitch(),
            \Vyuldashev\NovaPermission\NovaPermissionTool::make(),
            // new \Sereny\NovaPermissions\NovaPermissions(),


        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
    public function NovaSettings(): void
    {
        NovaSettings::addSettingsFields([
            Tabs::make(__('Main'), [

                Tabs::make(__('Site'), [

                    Image::make(__('Logo'), 'site_logo')->disk('public'),
                    Image::make(__('Icon'), 'site_icon')->disk('public'),

                    Text::make(__('Site Name In Arabic'), 'site_name_ar'),
                    Text::make(__('Site Name In English'), 'site_name_en'),

                    Text::make(__('Site Phone'), 'site_phone'),

                    Textarea::make(__('Site Description In Arabic'), 'site_description_ar'),
                    Textarea::make(__('Site Description In English'), 'site_description_en'),

                    Textarea::make(__('Analytics'), 'site_analytics'),

                ]),

                Tabs::make(__('Social Media'), [

                    URL::make(__('Twitter'), 'social_twitter'),
                    URL::make(__('Instagram'), 'social_instagram'),
                    URL::make(__('Facebook'), 'social_facebook'),
                    URL::make(__('Youtube'), 'social_youtube'),

                ]),

                Tabs::make(__('Contact Us'), [
                    Text::make(__('Title In Arabic'), 'contact_title_ar'),
                    Text::make(__('Title In English'), 'contact_title_en'),
                    Textarea::make(__('Description In Arabic'), 'contact_description_ar'),
                    Textarea::make(__('Description In English'), 'contact_description_en'),
                    Image::make(__('Main Image'), 'contact_main_image')
                        ->disk('public'),
                ]),

                Tabs::make(__('About Us'), [
                    Text::make(__('Title In Arabic'), 'about_title_ar'),
                    Text::make(__('Title In English'), 'about_title_en'),
                    Textarea::make(__('Description In Arabic'), 'about_description_ar'),
                    Textarea::make(__('Description In English'), 'about_description_en'),
                    Image::make(__('Main Image'), 'about_main_image')
                        ->disk('public'),

                ]),

                Tabs::make(__('Partners'), [
                    Text::make(__('Title In Arabic'), 'partner_title_ar'),
                    Text::make(__('Title In English'), 'partner_title_en'),
                    Textarea::make(__('Description In Arabic'), 'partner_description_ar'),
                    Textarea::make(__('Description In English'), 'partner_description_en'),

                ]),

                // Tabs::make(__('Prices'), []),

            ])->withToolbar(),

            Panel::make(__('Footer'), [

                Textarea::make(__('Footer Main Description In Arabic'), 'Footer_main_description_ar'),
                Textarea::make(__('Footer Main Description In English'), 'Footer_main_description_en'),

                Text::make(__('Footer Copyright - Arabic'), 'footer_copyright_ar'),
                Text::make(__('Footer Copyright - English'), 'footer_copyright_en'),

            ])->withToolbar(),
        ]);
    }


    public function customMenu() {
        Nova::mainMenu(function (HttpRequest $request) {
            return [
                // MenuSection::dashboard(Main::class)->icon('chart-bar'),

                MenuSection::make(__('Nova main'))->path('dashboards/main')->icon('chart-bar'),

                MenuSection::make(__('Individuals'), [
                    MenuItem::resource(Admin::class),
                    MenuItem::resource(User::class)->withBadge(fn () => UserModel::count(), 'info'),
                    MenuItem::resource(Contact::class)->withBadge(fn () =>  ContactModel::count(), 'danger'),
                ])->icon('user')->collapsable(),

                MenuSection::make(__('Content management'), [
                    MenuItem::resource(TermCondition::class),
                    MenuItem::resource(Policy::class),
                    MenuItem::resource(FrequentAskedQuestion::class),
                    MenuItem::resource(Partners::class),
                ])->icon('document-text')->collapsable(),

                // MenuSection::make(__('Sailing section'), [
                // ])->icon('document-text')->collapsable(),

                MenuSection::make(__('Settings'))->path('nova-settings/general#Main=site')->icon('adjustments'),

                MenuSection::make(__('Roles and permissions'), [
                    MenuItem::link(__('Roles'), 'resources/roles'),
                    MenuItem::link(__('Permissions'), 'resources/permissions')
                ])->icon('shield-check'),

            ];
        });

    }
}
