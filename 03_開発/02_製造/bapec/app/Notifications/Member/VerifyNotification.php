<?php

namespace App\Notifications\Member;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

use App\Helpers\Util\SystemHelper;

class VerifyNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = $this->verificationUrl($notifiable);
        return (new MailMessage)
                    ->subject('登録確認メール')
                    ->markdown('member.auth.verifyemail', ['url' => $url, 'user' => $notifiable]);
    }
    /**
     * リンク先を作成する。
     *
     * @param mixed  $notifiable
     * @return string URL
     */
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'member.verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', SystemHelper::getAppSettingValue('member.auth.limit'))),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }
}
