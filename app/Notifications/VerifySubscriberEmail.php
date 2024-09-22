<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
//use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;
use App\FredOS\Services\SeedsGetter;
use App\Models\Users\User;
use App\Models\Market\Price;
//use App\Entities\Users\Product;

class VerifySubscriberEmail extends VerifyEmail
{

  public $user;

  public function __construct(User $user)
  {
    $this->user = $user;
  }


  /**
   * Get the verify email notification mail message for the given URL.
   *
   * @param  string  $url
   * @return \Illuminate\Notifications\Messages\MailMessage
   */
  protected function buildMailMessage($url)
  {
    //use the checkout session object passed from the event to get the product and price information
    $price = $this->user->subscriptions()->latest()->price;
    $product = $this->user->subscriptions()->latest()->product;
    //$product = $price->products->where(['stripe_product_id' => ]);
    $sitename = config('app.name');
    //$productname = $product->name;
    //TEST:
    $priceamt = $price->getAmountString();
    $priceinterval = $price->renewal_interval;

    return (new MailMessage)
      ->subject('Welcome and Verify Email Address')
      ->line("Welcome to $sitename! Below is information about your new membership, but first, please click the button below to verify your email address.")
      ->action('Verify Email Address', $url)
      ->line('If you did not create an account, you may want to contact your credit card issuer, because a card with this email address was used to subscribe to our service.')
      ->line("You have subscribed to our {$product->name} plan with {$price->name} renewal. Your card will be billed $priceamt every $priceinterval. You may cancel at any time by going to your Member Stripe Portal (see below).")
      ->line('You should have received an email receipt from Stripe. If you do not have one, please visit your Portal to view the status of your payment (see below).')
      ->line("You can manage all aspects of your subscription at the Member Stripe Portal. Here you can cancel, upgrade, renew, change card details or payment method, etc.")
      //      ->line('You can visit your portal now with the button below. Doing this will also verify your email if you have not done so already')
      //      ->action('Your Stripe Portal', $this->portalUrl())
      ->line("A link to the Portal will be on your user menu at the top right of every page when you are logged in, and on your Personal Space.")
      ->line("Thank you again for joining our community and please reach out with any questions or feedback.")
      ->action('Verify Email Address', $url);
  }



  /**
   * Get the verification URL for the given notifiable.
   *
   * @param  mixed  $notifiable
   * @return string
   */
  protected function verificationUrl($notifiable)
  {
    if (static::$createUrlCallback) {
      return call_user_func(static::$createUrlCallback, $notifiable);
    }

    return URL::temporarySignedRoute(
      'subscriber.verify',
      Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
      [
        'id' => $notifiable->id,
        'hash' => sha1($notifiable->getEmailForVerification()),
      ]
    );
  }

  protected function portalUrl()
  {

    return URL::temporarySignedRoute(
      'user.portal.verify',
      Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
      [
        'id' => $this->user->id,
        'hash' => sha1($this->user->getEmailForVerification()),
      ]
    );
  }
}
