<?php

namespace App\Nova;

use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphToMany;
use Laravel\Nova\Fields\Password;
use Illuminate\Validation\Rules;
use Laravel\Nova\Fields\Email;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Admin extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Admin>
     */
    public static $model = \App\Models\Admin::class;

    public static $displayInNavigation = true;
    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name'
    ];


    public static function label()
    {
        return __('Admins');
    }

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            Image::make(__('Photo'),'image')->path('admins')->disk('public')
            ->rules('image'),

            Text::make(__('Name'),'name')
                ->sortable()
                ->rules('required', 'max:255'),

            Email::make(__('Email'),'email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            Password::make(__('Password'),'password')
                ->onlyOnForms()
                ->creationRules('required', Rules\Password::defaults())
                ->updateRules('nullable', Rules\Password::defaults()),

            MorphToMany::make(__('Roles'), 'roles', \Vyuldashev\NovaPermission\Role::class),
            MorphToMany::make(__('Permissions'), 'permissions', \Vyuldashev\NovaPermission\Permission::class),
        ];
    }



    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
