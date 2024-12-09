<?php

namespace Drupal\Tests\notification_services\Unit;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\node\NodeInterface;
use Drupal\notification_services\Services\NotificationCommonServices;
use Drupal\Tests\UnitTestCase;

/**
 * Tests the NotificationCommonServices service.
 */
class NotificationServicesTest extends UnitTestCase {
  /**
   * The service under test.
   *
   * @var \Drupal\notification_services\Services\NotificationCommonServices
   *   NotificationCommonServices.
   */
  protected $notificationService;

  /**
   * The mocked entity type manager.
   *
   * @var \PHPUnit\Framework\MockObject\MockObject|EntityTypeManager
   *   EntityTypeManager.
   */
  protected $entityTypeManager;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    // Mock the EntityTypeManager.
    $this->entityTypeManager = $this->createMock(EntityTypeManager::class);

    // Create an instance of the service, injecting the mocked dependencies.
    $this->notificationService = new NotificationCommonServices($this->entityTypeManager);
  }

  /**
   * Tests getTemplate method.
   */
  public function testGetTemplate() {
    // Mock the Node storage.
    $nodeStorage = $this->createMock(EntityStorageInterface::class);
    $this->entityTypeManager->method('getStorage')
      ->with('node')
      ->willReturn($nodeStorage);

    // Prepare the mocked node entities.
    $nodeMock = $this->createMock(NodeInterface::class);

    // Configure the storage to return the mocked node on loadByProperties.
    $nodeStorage->expects($this->once())
      ->method('loadByProperties')
      ->with([
        'type' => 'notification_templates',
        'field_template_key' => 'template_key_1',
        'status' => NodeInterface::PUBLISHED,
      ])
      ->willReturn([$nodeMock]);

    // Execute the method and assert the return value.
    $result = $this->notificationService->getTemplate('template_key_1');
    $this->assertEquals($nodeMock, $result);
  }

  /**
   * Tests getTemplate method with a template that does not exist.
   */
  public function testGetTemplateNotFound() {
    // Mock the Node storage.
    $nodeStorage = $this->createMock(EntityStorageInterface::class);
    $this->entityTypeManager->method('getStorage')
      ->with('node')
      ->willReturn($nodeStorage);

    // Configure the storage to return an empty array on loadByProperties.
    $nodeStorage->method('loadByProperties')
      ->willReturn([]);

    // Expect an exception to be thrown.
    $this->expectException(\Exception::class);
    $this->expectExceptionMessage("Template not found");

    // Execute the method.
    $this->notificationService->getTemplate('invalid_key');
  }

}
