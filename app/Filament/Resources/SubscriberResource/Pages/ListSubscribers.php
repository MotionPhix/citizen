<?php

namespace App\Filament\Resources\SubscriberResource\Pages;

use App\Filament\Resources\SubscriberResource;
use App\Models\Subscriber;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\MaxWidth;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;

class ListSubscribers extends ListRecords
{
  protected static string $resource = SubscriberResource::class;

  protected function getHeaderActions(): array
  {
    return [
      Actions\CreateAction::make()
        ->label('Add Subscriber')
        ->icon('heroicon-o-plus'),

      Actions\ActionGroup::make([
        Actions\Action::make('import_subscribers')
          ->label('Import Subscribers')
          ->icon('heroicon-o-arrow-up-tray')
          ->color('success')
          ->modalWidth(MaxWidth::TwoExtraLarge)
          ->form([
            Forms\Components\Section::make('Import Instructions')
              ->schema([
                Forms\Components\Placeholder::make('instructions')
                  ->label('')
                  ->content(new HtmlString('
                                        <div class="space-y-4 text-sm">
                                            <div>
                                                <h4 class="font-semibold text-gray-900 dark:text-white mb-2">CSV Format Requirements:</h4>
                                                <ul class="list-disc list-inside space-y-1 ml-4 text-gray-700 dark:text-gray-300">
                                                    <li>First row should contain column headers</li>
                                                    <li>Required column: <code class="bg-gray-100 dark:bg-gray-800 px-1 py-0.5 rounded text-xs font-mono">email</code></li>
                                                    <li>Optional columns: <code class="bg-gray-100 dark:bg-gray-800 px-1 py-0.5 rounded text-xs font-mono">name</code>, <code class="bg-gray-100 dark:bg-gray-800 px-1 py-0.5 rounded text-xs font-mono">status</code></li>
                                                    <li>Status values: <code class="bg-gray-100 dark:bg-gray-800 px-1 py-0.5 rounded text-xs font-mono">subscribed</code> or <code class="bg-gray-100 dark:bg-gray-800 px-1 py-0.5 rounded text-xs font-mono">unsubscribed</code> (defaults to subscribed)</li>
                                                </ul>
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Example CSV:</h4>
                                                <pre class="bg-gray-100 dark:bg-gray-800 p-3 rounded-lg text-xs font-mono text-gray-800 dark:text-gray-200 border">email,name,status
john@example.com,John Doe,subscribed
jane@example.com,Jane Smith,subscribed
bob@example.com,Bob Johnson,unsubscribed</pre>
                                            </div>
                                        </div>
                                    '))
                  ->columnSpanFull(),
              ])
              ->collapsible(),

            Forms\Components\Section::make('Upload File')
              ->schema([
                Forms\Components\FileUpload::make('csv_file')
                  ->label('CSV File')
                  ->acceptedFileTypes(['text/csv', '.csv'])
                  ->maxSize(5120) // 5MB
                  ->required()
                  ->helperText('Upload a CSV file with subscriber data (max 5MB)')
                  ->columnSpanFull(),

                Forms\Components\Select::make('duplicate_handling')
                  ->label('Duplicate Email Handling')
                  ->options([
                    'skip' => 'Skip duplicates (recommended)',
                    'update' => 'Update existing subscribers',
                    'error' => 'Stop import on duplicates',
                  ])
                  ->default('skip')
                  ->required()
                  ->helperText('How to handle emails that already exist in the system'),

                Forms\Components\Toggle::make('send_welcome_email')
                  ->label('Send Welcome Email')
                  ->default(false)
                  ->helperText('Send a welcome email to newly imported subscribers'),
              ])
              ->columns(2),
          ])
          ->action(function (array $data): void {
            $csvPath = storage_path('app/' . $data['csv_file']);

            if (!file_exists($csvPath)) {
              Notification::make()
                ->danger()
                ->title('File not found')
                ->body('The uploaded CSV file could not be found.')
                ->send();
              return;
            }

            $results = $this->importSubscribersFromCsv(
              $csvPath,
              $data['duplicate_handling'],
              $data['send_welcome_email']
            );

            // Clean up uploaded file
            if (file_exists($csvPath)) {
              unlink($csvPath);
            }

            if ($results['errors'] > 0) {
              Notification::make()
                ->warning()
                ->title('Import completed with issues')
                ->body("Imported {$results['imported']} subscribers, skipped {$results['skipped']} duplicates, {$results['errors']} errors occurred.")
                ->send();
            } else {
              Notification::make()
                ->success()
                ->title('Import completed successfully')
                ->body("Successfully imported {$results['imported']} subscribers. Skipped {$results['skipped']} duplicates.")
                ->send();
            }
          }),

        Actions\Action::make('export_subscribers')
          ->label('Export Subscribers')
          ->icon('heroicon-o-arrow-down-tray')
          ->color('info')
          ->form([
            Forms\Components\Select::make('status_filter')
              ->label('Export Status')
              ->options([
                'all' => 'All Subscribers',
                'subscribed' => 'Subscribed Only',
                'unsubscribed' => 'Unsubscribed Only',
              ])
              ->default('subscribed')
              ->required(),
          ])
          ->action(function (array $data): \Symfony\Component\HttpFoundation\StreamedResponse {
            $query = Subscriber::query();

            if ($data['status_filter'] !== 'all') {
              $query->where('status', $data['status_filter']);
            }

            $subscribers = $query->get();

            $headers = [
              'Content-Type' => 'text/csv',
              'Content-Disposition' => 'attachment; filename="subscribers_export_' . now()->format('Y-m-d') . '.csv"',
            ];

            return response()->stream(function () use ($subscribers) {
              $handle = fopen('php://output', 'w');

              // Add CSV headers
              fputcsv($handle, ['email', 'name', 'status', 'subscribed_at', 'unsubscribed_at']);

              // Add subscriber data
              foreach ($subscribers as $subscriber) {
                fputcsv($handle, [
                  $subscriber->email,
                  $subscriber->name,
                  $subscriber->status,
                  $subscriber->created_at?->format('Y-m-d H:i:s'),
                  $subscriber->unsubscribed_at?->format('Y-m-d H:i:s'),
                ]);
              }

              fclose($handle);
            }, 200, $headers);
          }),

        Actions\Action::make('download_template')
          ->label('Download CSV Template')
          ->icon('heroicon-o-document-arrow-down')
          ->color('gray')
          ->action(function (): \Symfony\Component\HttpFoundation\StreamedResponse {
            $headers = [
              'Content-Type' => 'text/csv',
              'Content-Disposition' => 'attachment; filename="subscriber_import_template.csv"',
            ];

            return response()->stream(function () {
              $handle = fopen('php://output', 'w');

              // Add CSV headers
              fputcsv($handle, ['email', 'name', 'status']);

              // Add example rows
              fputcsv($handle, ['john@example.com', 'John Doe', 'subscribed']);
              fputcsv($handle, ['jane@example.com', 'Jane Smith', 'subscribed']);
              fputcsv($handle, ['bob@example.com', 'Bob Johnson', 'unsubscribed']);

              fclose($handle);
            }, 200, $headers);
          }),
      ])
        ->dropdownPlacement('bottom-end')
        ->label('Actions')
        ->icon('heroicon-o-ellipsis-vertical')
        ->color('gray')
        ->button(),
    ];
  }

  /**
   * Import subscribers from CSV file
   */
  protected function importSubscribersFromCsv(string $csvPath, string $duplicateHandling, bool $sendWelcomeEmail = false): array
  {
    $imported = 0;
    $skipped = 0;
    $errors = 0;
    $errorMessages = [];

    try {
      $handle = fopen($csvPath, 'r');

      if (!$handle) {
        return ['imported' => 0, 'skipped' => 0, 'errors' => 1, 'messages' => ['Could not open CSV file']];
      }

      // Read header row
      $headers = fgetcsv($handle);

      if (!$headers || !in_array('email', $headers)) {
        fclose($handle);
        return ['imported' => 0, 'skipped' => 0, 'errors' => 1, 'messages' => ['CSV must contain an "email" column']];
      }

      // Map headers to indices
      $emailIndex = array_search('email', $headers);
      $nameIndex = array_search('name', $headers);
      $statusIndex = array_search('status', $headers);

      // Process each row
      $rowNumber = 1;
      while (($row = fgetcsv($handle)) !== false) {
        $rowNumber++;

        // Skip empty rows
        if (empty(array_filter($row))) {
          continue;
        }

        $email = trim($row[$emailIndex] ?? '');
        $name = trim($row[$nameIndex] ?? '');
        $status = trim($row[$statusIndex] ?? 'subscribed');

        // Validate email
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $errors++;
          $errorMessages[] = "Row {$rowNumber}: Invalid email '{$email}'";
          continue;
        }

        // Validate status
        if (!in_array($status, ['subscribed', 'unsubscribed'])) {
          $status = 'subscribed';
        }

        // Check for existing subscriber
        $existingSubscriber = Subscriber::where('email', $email)->first();

        if ($existingSubscriber) {
          if ($duplicateHandling === 'skip') {
            $skipped++;
            continue;
          } elseif ($duplicateHandling === 'error') {
            $errors++;
            $errorMessages[] = "Row {$rowNumber}: Email '{$email}' already exists";
            continue;
          } elseif ($duplicateHandling === 'update') {
            $existingSubscriber->update([
              'name' => $name ?: $existingSubscriber->name,
              'status' => $status,
              'unsubscribed_at' => $status === 'unsubscribed' ? now() : null,
            ]);
            $imported++;
            continue;
          }
        }

        // Create new subscriber
        try {
          Subscriber::create([
            'email' => $email,
            'name' => $name ?: null,
            'status' => $status,
            'unsubscribed_at' => $status === 'unsubscribed' ? now() : null,
            'preferences' => (new Subscriber())->getDefaultPreferences(),
          ]);

          $imported++;

          // TODO: Send welcome email if enabled
          // if ($sendWelcomeEmail && $status === 'subscribed') {
          //   // Send welcome email logic here
          // }

        } catch (\Exception $e) {
          $errors++;
          $errorMessages[] = "Row {$rowNumber}: Failed to create subscriber - " . $e->getMessage();
        }
      }

      fclose($handle);

    } catch (\Exception $e) {
      return ['imported' => 0, 'skipped' => 0, 'errors' => 1, 'messages' => ['Import failed: ' . $e->getMessage()]];
    }

    return [
      'imported' => $imported,
      'skipped' => $skipped,
      'errors' => $errors,
      'messages' => $errorMessages
    ];
  }
}
