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
    // Get the config form settings.
    $user_timeformat = $this->configFactory->get('user_timeformat.settings');
    $timezone = !empty($user_timeformat->get('timezone')) ? $user_timeformat->get('timezone') : 'Asia/Kolkata';
    $time_format = !empty($user_timeformat->get('time')) ? $user_timeformat->get('time') : 'h:i a';
    $date_format = !empty($user_timeformat->get('date')) ? $user_timeformat->get('date') : 'l, j F Y';
    // Get the current time stamp value.
    $time_stamp = $this->timeService->getCurrentTime();
    // Let's Format the time & date as required.
    $time_formatted = $this->dateFormatService->format(
      $time_stamp, 'custom', $time_format, $timezone
    );
    $date_formatted = $this->dateFormatService->format(
      $time_stamp, 'custom', $date_format, $timezone
    );

    return [
      'time_format' => $time_formatted,
      'date_format' => $date_formatted,
    ];
  }

}
