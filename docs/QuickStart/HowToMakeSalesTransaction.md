# Satış İşlemi Nasıl Gerçekleştirilir ?
[İçindekiler](/docs/icindekiler.md) > [Hızlı Başlangıç](/docs/QuickStart.md) > Satış İşlemi Nasıl Gerçekleştirilir ?

## Amaç:
Kullanıcının ödeme aracını (şu an için yalnızca kredi kartı) kullanarak kullanıcının kredi hesabından işletme hesabına para aktarma işlemini gerçekleştirmek.

## Nasıl ?
Satış işlemini gerçekleştirmek için aşağıdaki işlemleri sırasıyla gerçekleştirmelisiniz.

* [Sanal Pos Konfigürasyonunun Oluşturulması](#sanal-pos-konfig%C3%BCrasyonunun-olu%C5%9Fturulmas%C4%B1)
* [Satış İsteğinin Oluşturulması](#sat%C4%B1%C5%9F-%C4%B0ste%C4%9Finin-olu%C5%9Fturulmas%C4%B1)
* Satış İşleminin Uygun Sanal Pos Üzerinden Gerçekleştirilmesi

## Sanal Pos Konfigürasyonunun Oluşturulması

Seçtiğiniz sanal pos adaptörünün (Bkz. [Adaptörler](/docs/References/Adapters.md)) ödeme sistemi servisiyle iletişim kurabilmesi için kendisine uygun bir konfigürasyon objesi oluşturmalısınız. (Bkz. Konfigürasyon Tipleri)

Dökümandaki örnekler *NestPay* ile gerçekleştirileceği için konfigürasyon tipini \Paranoia\Configuration\NestPay olarak seçiyoruz.

```php
use Paranoia\Configuration\NestPay as NestPayConfig;

$configuration = new NestPayConfig();
$configuration->setMode('PROD')
    ->setClientId('700655000100')
    ->setUsername('ISBANKAPI')
    ->setPassword('ISBANK07')
    ->setApiUrl('https://entegrasyon.asseco-see.com.tr/fim/api');

```

Her konfigürasyon tipi ödeme sistemi tipine özel olarak farklı argümanlar içermektedir. Seçtiğiniz ödeme sistemi adaptörü ile ilgili konfigürasyon tipleri için [Konfigürasyon Tipleri](/docs/References/ConfigurationTypes.md) dökümanını inceleyebilirsiniz.


## Satış İsteğinin Oluşturulması

Satış işlemi ile ilgili sipariş numarası, tutar, ödeme aracına ait bilgiler gibi verilerin ödeme sistemi servisine iletilebilmesi için sipariş isteği oluşturulması gerekmektedir. Aşağıda örnek sipariş isteği görülüyor.

```php
use Paranoia\Payment\Request\SaleRequest;
use Paranoia\Payment\Adapter\AdapterAbstract;

$request = new SaleRequest();
$request->setOrderId('PRN1558769234')
    ->setInstallment($installment)
    ->setAmount($amount)
    ->setCurrency(AdapterAbstract::CURRENCY_TRY)
    ->setCardNumber('4508034508034509')
    ->setExpireMonth(12)
    ->setExpireYear(16)
    ->setSecurityCode('000');
```

Satış isteğinin alabileceği diğer parametreleri görebilmek için [bu dökümanı](/docs/References/RequestTypes/SaleRequest.md) inceleyebilirsiniz.

## Satış İşleminin Uygun Sanal Pos Üzerinden Gerçekleştirilmesi

Satış isteğini ödeme sistemi servisine iletmek için [bu dökümanda](/docs/References/Adapters.md) tanımlanan adaptörlerden birini seçerek sale() metodunu kullanmalısınız.

Örnekte belirtilen **$configuration** değişkeni, [Sanal Pos Konfigürasyonunun Oluşturulması](#sanal-pos-konfig%C3%BCrasyonunun-olu%C5%9Fturulmas%C4%B1) adımında oluşturulan NestPay tipindeki konfigürasyon nesnesini içermektedir.

**$request** değişkeni, [Satış İsteğinin Oluşturulması](#sat%C4%B1%C5%9F-%C4%B0ste%C4%9Finin-olu%C5%9Fturulmas%C4%B1) adımında oluşturulan satış isteği objesini içermektedir.

```php
use Paranoia\Payment\Adapter\NestPay;

$adapter = new NestPay($configuration);

/** @var $response \Paranoia\Payment\Response\SaleResponse */
$response = $adapter->sale($request);

```

İşlemin başarılı olup olmadığını tespit etmek için yanıt objesinin **isSuccess()** metodunu kullanabilirsiniz. Satış yanıtı objesi ile ilgili diğer özellikler için [bu dökümanı](/docs/References/ResponseTypes/SaleResponse.md) inceleyebilirsiniz.