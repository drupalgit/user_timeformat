<?php

namespace Drupal\user_timeformat\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\user_timeformat\CurrentTime;

/**
 * Provides a 'User time' Block.
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
   * @var currentTime
   */
  protected $currentTime;

  /**
   * The params used.
   *
   * @param array $configuration
   *   The configuration object.
   * @param string $plugin_id
   *   The plugin_id.
   * @param mixed $plugin_definition
   *   The plugin_definition.
   * @param \Drupal\Core\Session\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Drupal\Core\Session\CurrentTime $current_time
   *   The current time service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactoryInterface $config_factory, CurrentTime $current_time) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->configFactory = $config_factory;
    $this->currentTime = $current_time;
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
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $time = '';
    $date = '';
    $country = '';
    $city = '';
    // Get the config form values.
    $user_timeformat = $this->configFactory->get('user_timeformat.settings');
    $country = !empty($user_timeformat->get('country')) ? $user_timeformat->get('country') : '';
    $city = !empty($user_timeformat->get('city')) ? $user_timeformat->get('city') . ", " : '';
    // Get current time & date format.
    $service_format = $this->currentTime->timeFormate();
    if ($service_format) {
      $time = $service_format['time_format'];
      $date = $service_format['date_format'];
    }
    $build = [
      '#theme' => 'user_timeformat',
      '#time' => $time,
      '#date' => $date,
      '#country' => $country,
      '#city' => $city,
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
    return ['config:user_timeformat.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    return 59;
  }

}
