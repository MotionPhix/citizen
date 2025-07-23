<?php

namespace App\Filament\Resources\NewsletterContentResource\Pages;

use App\Filament\Resources\NewsletterContentResource;
use App\Models\NewsletterContent;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListNewsletterContents extends ListRecords
{
    protected static string $resource = NewsletterContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Content')
                ->badge(NewsletterContent::count()),

            'stories' => Tab::make('Stories')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('type', 'story'))
                ->badge(NewsletterContent::where('type', 'story')->count()),

            'events' => Tab::make('Events')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('type', 'event'))
                ->badge(NewsletterContent::where('type', 'event')->count()),

            'updates' => Tab::make('Updates')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('type', 'update'))
                ->badge(NewsletterContent::where('type', 'update')->count()),

            'announcements' => Tab::make('Announcements')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('type', 'announcement'))
                ->badge(NewsletterContent::where('type', 'announcement')->count()),

            'featured' => Tab::make('Featured')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_featured', true))
                ->badge(NewsletterContent::where('is_featured', true)->count()),
        ];
    }
}
