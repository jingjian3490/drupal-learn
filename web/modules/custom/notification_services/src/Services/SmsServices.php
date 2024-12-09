<?php

namespace Drupal\notification_services\Services;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use GuzzleHttp\ClientInterface;

/**
 * ChuanglanSmsApi.
 */
class SmsServices {

  const API_SEND_URL = 'http://XXX/msg/send/json';
  const API_ACCOUNT = '';
  const API_PASSWORD = '';


  /**
   * Guzzle HTTP.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * The logger factory.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  protected $loggerFactory;
  /**
   * ConfigFactory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs a new SmsService object.
   *
   * @param \GuzzleHttp\ClientInterface $http_client
   *   The HTTP client.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $logger_factory
   *   The logger factory.
   */
  public function __construct(
    ClientInterface $http_client,
    ConfigFactoryInterface $config_factory,
    LoggerChannelFactoryInterface $logger_factory,
  ) {
    $this->httpClient = $http_client;
    $this->configFactory = $config_factory;
    $this->loggerFactory = $logger_factory->get('notification_services');
  }

  /**
   * Send SMS.
   *
   * @param string $mobile
   *   Number phone.
   * @param string $msg
   *   Message content.
   * @param string $need_status
   *   Whether a status report is required.
   *
   * @return string
   *   API result.
   */
  public function sendSms(string $mobile, string $msg, bool $need_status = TRUE): array {
    $config = $this->configFactory->get('notification_services.settings');
    $api_url = $config->get('sms_api_url');
    $credentials = $this->getCredentials();

    $postData = [
      'account' => $credentials['account'],
      'password' => $credentials['password'],
      'msg' => urlencode($msg),
      'phone' => $mobile,
      'report' => $need_status ? 'true' : 'false',
    ];

    try {
      $response = $this->httpClient->post($api_url, [
        'json' => $postData,
        'headers' => [
          'Content-Type' => 'application/json; charset=utf-8',
        ],
        'timeout' => 60,
        'verify' => FALSE,
      ]);

      return json_decode($response->getBody()->getContents(), TRUE);
    }
    catch (\Exception $e) {
      $this->loggerFactory->error('SMS sending failed: @error', ['@error' => $e->getMessage()]);
      return ['error' => $e->getMessage()];
    }
  }

  /**
   * Gets the decrypted API credentials.
   *
   * @return array
   *   The decrypted credentials.
   */
  protected function getCredentials(): array {
    $config = $this->configFactory->get('notification_services.settings');
    $encrypted_credentials = $config->get('sms_api_credentials');

    if (!$encrypted_credentials) {
      return ['account' => '', 'password' => ''];
    }

    $decoded = base64_decode($encrypted_credentials);
    return json_decode($decoded, TRUE) ?: ['account' => '', 'password' => ''];
  }

  /**
   * 发送短信.
   *
   * @param string $mobile
   *   Number phone.
   * @param string $msg
   *   SMS content.
   * @param string $need_status
   *   Is or not need report of status.
   */
  public function sendSmsCurl(string $mobile, string $msg, string $need_status = 'true') {
    $postArr = [
      'account' => self::API_ACCOUNT,
      'password' => self::API_PASSWORD,
      'msg' => urlencode($msg),
      'phone' => $mobile,
      'report' => $need_status,
    ];
    $result = $this->curlPost(self::API_SEND_URL, $postArr);
    return $result;
  }

  /**
   * 通过CURL发送HTTP请求.
   */
  private function curlPost($url, $postFields) {
    $postFields = json_encode($postFields);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      'Content-Type: application/json; charset=utf-8',
        // json版本需要填写  Content-Type: application/json;.
    ]
    );
    // 若果报错 name lookup timed out 报错时添加这一行代码.
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $ret = curl_exec($ch);
    if (FALSE == $ret) {
      $result = curl_error($ch);
    }
    else {
      $rsp = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      if (200 != $rsp) {
        $result = "请求状态 " . $rsp . " " . curl_error($ch);
      }
      else {
        $result = $ret;
      }
    }
    curl_close($ch);
    return $result;
  }

}
