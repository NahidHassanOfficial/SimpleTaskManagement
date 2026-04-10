<?php

namespace App\Http\Middleware;

use App\Enums\TaskSeverity;
use App\Enums\TaskStatus;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'enums' => [
                'statuses' => collect(TaskStatus::cases())->map(fn ($status) => [
                    'value' => $status->value,
                    'label' => $status->label(),
                    'color' => $status->color(),
                    'icon' => $status->icon(),
                ]),
                'severities' => collect(TaskSeverity::cases())->map(fn ($severity) => [
                    'value' => $severity->value,
                    'label' => $severity->label(),
                    'color' => $severity->color(),
                ]),
            ],
        ]);

    }
}
