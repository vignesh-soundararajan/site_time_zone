<?php

namespace Drupal\site_time_zone;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Provides Date time based on custom timezone .
 */
class CustomTimeZoneService {
  /**
   * The config factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs a new WorkspaceSwitcherBlock instance.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->configFactory = $config_factory;
  }

  /**
   * Returns list of date time based on custom time zone form config form.
   */
  public function getData() {
    $admin_config = $this->configFactory->get('stz.settings');
    $tz = $admin_config->get('zone');
	if(!empty($tz)) {
		$date = new DrupalDateTime('now', 'UTC');
		$date->setTimezone(new \DateTimeZone($tz));
		return $date->format('jS M Y - g:i A');
	}
	else {
		return FALSE;
	}
  }

}
