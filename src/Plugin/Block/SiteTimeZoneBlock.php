<?php

namespace Drupal\site_time_zone\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Locale\CountryManagerInterface;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Provides a 'Custom Site TimeZone' Block.
 *
 * @Block(
 *   id = "site_timezone_block",
 *   subject = @Translation("Custom Site TimeZone"),
 *   admin_label = @Translation("Custom Site TimeZone")
 * )
 */
class SiteTimeZoneBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The country manager.
   *
   * @var \Drupal\Core\Locale\CountryManagerInterface
   */
  protected $countryManager;

  /**
   * The config factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs a new WorkspaceSwitcherBlock instance.
   *
   * @param array $configuration
   *   The plugin configuration.
   * @param string $plugin_id
   *   The plugin ID.
   * @param mixed $plugin_definition
   *   The plugin definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Locale\CountryManagerInterface $country_manager
   *   The country manager.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, CountryManagerInterface $country_manager, ConfigFactoryInterface $config_factory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
    $this->countryManager = $country_manager;
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('country_manager'),
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    $admin_config = $this->configFactory->get('stz.settings');
    $data['country'] = $admin_config->get('country');
    $data['city'] = $admin_config->get('city');
    $data['zone'] = \Drupal::service('site_time_zone.custom_timezone_services')->getData();
    // print_r($data);die;
    if (!empty($data)) {

      return [
        '#theme' => 'custom_site_timezone',
        '#data' => $data,
      ];
    }
    else {
      $data['help_text'] = $this->t('Please go to the Custom timezone  admin setting page and select the country timezone as per your requirement.');
      return [
        '#theme' => 'custom_site_timezone',
        '#data' => $data,
      ];
    }

    // print_r($data);die;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    return Cache::mergeTags(parent::getCacheTags(), [
      'config:stz.settings',
    ]);
  }

}
