<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListUsers extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $user = auth()->user();
        $tabs = [
            null => Tab::make('All')->query(fn ($query) => $query->where('tanggal_lulus_pondok', '=', null)),
            'Teman Kelas' => Tab::make()->query(fn ($query) => $query->where('kelas', '=', $user->kelas)->where('tanggal_lulus_pondok', '=', null)),
        ];

        if ($user->isSuperAdmin()) {
            $tabs['Ketua Kelas'] = Tab::make()->query(fn ($query) => $query->with('roles')->whereRelation('roles', 'name', '=', 'ketua_kelas'));
            $tabs['DMC Pasus'] = Tab::make()->query(fn ($query) => $query->whereHas('roles', function ($query) {
                $query->where('name', 'like', 'dmcp%');
            }));
            $tabs['Alumni'] = Tab::make()->query(fn ($query) => $query->where('tanggal_lulus_pondok', '!=', null));
            $tabs['Super Admin'] = Tab::make()->query(fn ($query) => $query->with('roles')->whereRelation('roles', 'name', '=', config('filament-shield.super_admin.name')));
        }

        return $tabs;
    }

    protected function getTableQuery(): Builder
    {

        $model = (new (static::$resource::getModel()))->with('roles');

        if (isNotSuperAdmin()) {
            $model = $model->whereDoesntHave('roles', function ($query) {
                $query->where('name', '=', config('filament-shield.super_admin.name'));
            });
        }

        return $model;
    }
}
