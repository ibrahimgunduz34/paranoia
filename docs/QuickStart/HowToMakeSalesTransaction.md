# Satış İşlemi Nasıl Gerçekleştirilir ?

## Amaç:
Kullanıcının ödeme aracını (şu an için yalnızca kredi kartı) kullanarak kullanıcının kredi hesabından işletme hesabına para aktarma işlemini gerçekleştirmek.

## Nasıl ?
Satış işlemini gerçekleştirmek için aşağıdaki işlemleri sırasıyla gerçekleştirmelisiniz.

* [Sanal Pos Konfigürasyonunun Oluşturulması](#sanal-pos-konfig%C3%BCrasyonunun-olu%C5%9Fturulmas%C4%B1)
* Satış İsteğinin Oluşturulması
* Satış İşleminin Uygun Sanal Pos Üzerinden Gerçekleştirilmesi

## Sanal Pos Konfigürasyonunun Oluşturulması

Seçtiğiniz sanal pos adaptörünün (Bkz. Adaptörler) ödeme sistemi servisiyle iletişim kurabilmesi için kendisine uygun bir konfigürasyon objesi oluşturmalısınız. (Bkz. Konfigürasyon Tipleri)

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
