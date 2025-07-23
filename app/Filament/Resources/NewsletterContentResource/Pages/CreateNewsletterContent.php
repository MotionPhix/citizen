<?php

namespace App\Filament\Resources\NewsletterContentResource\Pages;

use App\Filament\Resources\NewsletterContentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateNewsletterContent extends CreateRecord
{
    protected static string $resource = NewsletterContentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ensure metadata is properly formatted
        if (empty($data['metadata'])) {
            $data['metadata'] = [];
        }

        // Set default order if not provided
        if (!isset($data['order']) || $data['order'] === null) {
            $data['order'] = 0;
        }

        return $data;
    }
}
