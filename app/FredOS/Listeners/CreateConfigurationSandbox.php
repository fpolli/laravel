<?php

namespace Laravel\Octane\Listeners;

use App\FredOS\Services\SeedsConfig;

class CreateConfigurationSandbox
{
  protected SeedsConfig $sitesData;

  protected function setDomainConfig()
  {
    $domainHost = $_SERVER['SERVER_NAME'];

    if ($domainHost == 'localhost') {
      return;
    }

    $sitekey = $this->sitesData->Lookup($domainHost);
    $site = $this->sitesData->getSite($sitekey);

    foreach ($site['environment'] as $key => $value) {
      $_ENV[$key] = $value;
    }

    $_ENV['APP_URL'] = $domainHost;
    $_ENV['SESSION_DOMAIN'] = $domainHost;

    $site['config']['app.url'] = $domainHost;
    $site['config']['session.domain'] = $domainHost;

    config($site['config']);

    config(['routedata' => $this->sitesData->getRouteData($sitekey)]);

    config($site['mail']);
  }

  public function __construct(SeedsConfig $sitesData)
  {
    $this->sitesData = $sitesData;
  }

  /**
   * Handle the event.
   *
   * @param  mixed  $event
   */
  public function handle($event): void
  {
    $event->sandbox->instance('config', clone $event->sandbox['config']);
    $this->setDomainConfig();
  }
}
