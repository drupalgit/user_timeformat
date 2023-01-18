<?php

namespace Drupal\user_timeformat;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Custom service file.
 */
class CurrentTime {

  /**
   * The config factory.
   *
   * @var ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The Date formater.
   *
   * @var DateFormatterInterface
   */
  protected $dateFormatService;

  /**
   * The time interface.
   *
   * @var TimeInterface
   */
  protected $timeService;

  /**
   * The datetime.time service.
   *
   * @param \Drupal\Core\Session\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Drupal\Component\Datetime\DateFormatterInterface $date_format
   *   The Date formater.
   * @param \Drupal\Component\Datetime\TimeInterface $time_service
   *   The time interface.
   */
  public function __construct(ConfigFactoryInterface $config_factory, DateFormatterInterface $date_format, TimeInterface $time_service) {
    $this->configFactory = $config_factory;
    $this->dateFormatService = $date_format;
    $this->timeService = $time_service;
  }

  /**
   * Show the author of the node.
   */
  public function timeFormate() {
    $user_timeformat = $this->configFactory->get('user_timeformat.settings');
    $country = $user_timeformat->get('country');
    $city = $user_timeformat->get('city');
    $timezone = $user_timeformat->get('timezone');
    $formatted = $this->dateFormatService->format(
      $this->timeService->getCurrentTime(), 'custom', 'jS M Y - h:i A', $timezone
    );

    return $formatted;
  }

}
