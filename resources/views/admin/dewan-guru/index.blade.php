@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="card bg-white">
        <div class="card-header border-b border-blueGray-200">
            <div class="card-header-container">
                <h6 class="card-title">
                    {{ trans('cruds.dewanGuru.title_singular') }}
                    {{ trans('global.list') }}
                </h6>

                @can('dewan_guru_create')
                    <a class="btn btn-indigo" href="{{ route('admin.dewan-guru.create') }}">
                        {{ trans('global.add') }} {{ trans('cruds.dewanGuru.title_singular') }}
                    </a>
                @endcan
            </div>
        </div>
        @livewire('admin.dewan-guru.index')

    </div>
</div>
@endsection