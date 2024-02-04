<?php
namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Spatie\Backup\Notifications\Notifications\BackupWasSuccessfulNotification as Vendor;

class BackupWasSuccessfulNotification extends Vendor
{
    public function toMail(): MailMessage
    {
        $mailMessage = (new MailMessage())
            ->cc(env('BACKUP_MAIL_CC'))
            ->from(config('backup.notifications.mail.from.address', config('mail.from.address')), config('backup.notifications.mail.from.name', config('mail.from.name')))
            ->subject(trans('backup::notifications.backup_successful_subject', [ 'application_name' => 'Passport App' ]))
            ->line(trans('backup::notifications.backup_successful_body', [ 'application_name' => 'Passport App', 'disk_name' => $this->diskName() ]))
            ->action('Download Backup', route('backup.download.latest'));

        $this->backupDestinationProperties()->each(function ($value, $name) use ($mailMessage) {
            $mailMessage->line("{$name}: $value");
        });

        return $mailMessage;
    }
}
