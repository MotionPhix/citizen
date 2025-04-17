<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
  protected static string $resource = UserResource::class;

  protected function getHeaderActions(): array
  {
    return [
      Actions\ViewAction::make(),

      Actions\DeleteAction::make()
        ->action(function () {
          // Prevent deleting the last admin user
          if ($this->record->isAdmin()) {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
              throw new \Exception('Cannot delete the last administrator.');
            }
          }

          $this->record->delete();
        }),
    ];
  }
}
