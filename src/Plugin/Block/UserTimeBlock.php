<?php

namespace Drupal\user_timeformat\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\user_timeformat\CurrentTime;
use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Cache\Cache;

/**
 * Provides a 'Hello' Block.
 *
 * @Block(
 *   id = "user_time_block",
 *   admin_label = @Translation("User Time block"),
 *   category = @Translation("User Time block"),
 * )
 */
class UserTimeBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The config factory.
   *
   * @var ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The time interface.
   *
   * @var CurrentTime
   */
  protected $currentTime;

  /**
   * The time interface.
   *
   * @var TimeInterface
   */
  protected $timeService;

  /**
   * The Date formater.
   *
   * @var DateFormatterInterface
   */
  protected $dateFormatService;

  /**
   * @param array $configuration
   *   The configuration object.
   * @param string $plugin_id
   *   The plugin_id.
   * @param mixed $plugin_definition
   *   The plugin_definition.
   * @param \Drupal\Core\Session\ConfigFactoryInterface $config_factory
   *   The config factory.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactoryInterface $config_factory, CurrentTime $current_time, TimeInterface $time_service, DateFormatterInterface $date_format) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->configFactory = $config_factory;
    $this->CurrentTime = $current_time;
    $this->timeService = $time_service;
    $this->dateFormatService = $date_format;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory'),
      $container->get('user_timeformat.current_time'),
      $container->get('datetime.time'),
      $container->get('date.formatter')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    //$format = \Drupal::service('user_timeformat.current_time')->timeFormate();
    $format = $this->CurrentTime->timeFormate();
    $time = '';
    $date = '';
    $location = '';
    $city = '';

    $user_timeformat = $this->configFactory->get('user_timeformat.settings');
    $country = $user_timeformat->get('country');
    $city = $user_timeformat->get('city');
    $timezone = $user_timeformat->get('timezone');

    if ($format) {
      $time = $this->dateFormatService->format($this->timeService->getCurrentTime(), 'custom', 'h:i A', $timezone);
      $date = $this->dateFormatService->format($this->timeService->getCurrentTime(), 'custom', 'l j F Y', $timezone);
    }
    $build = [
      '#theme' => 'custom_timeformat',
      '#time' => $time,
      '#date' => $date,
      '#location' => $city . ", " . $country,
      '#cache' => [
        'tags' => $this->getCacheTags(),
        'max-age' => $this->getCacheMaxAge()
      ],
    ];
    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    return ['config:user_timeformat.settings' ];
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    return 30;
  }
}
