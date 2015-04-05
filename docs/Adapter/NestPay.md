# NestPay

[İçindekiler](/docs/icindekiler.md) > Referans Klavuzu > Adaptörler > NestPay

NestPay, Est firması tarafından geliştirilen NestPay sanal pos alt yapısını kullanan bankalar için geliştirilen adaptör sınıfıdır. NestPay sınıfını kullanarak ilgili bankalarda satış, iptal, iade, ön izin ve ön izin işleminin satışa dönüştürülmesi gibi işlemleri gerçekleştirebilirsiniz.

**İsim Uzayı :**<br/>
\Paranoia\Payment\Adapter

**Sınıf Adı :**<br/>
NestPay

**Dosya :**<br/>
/src/Paranoia/Payment/Adapter/NestPay.php

## Metodlar :

### __construct()

**__construct()** metodu, sınıfın kurucu metodudur. İlgili banka API sine ulaşabilmek için kullanılan erişim bilgileri ile donatılmış konfigürasyon sınıfını argüman olarak alır.

**Söz dizimi :**<br/>
(void) NestPay::__construct([\Paranoia\Configuration\NestPay](/docs/Configuration/NestPay.md) $configuration)


### sale() :

**sale()** metodu, satış işleminin gerçekleştirilmesi için kullanılır. 

**Söz dizimi :** <br/>
([SaleResponse](/docs/SaleResponse.md)) NestPay::sale([SaleRequest](/docs/SaleRequest.md) $request)

### cancel() :
**cancel()** metodu, iptal işlemi için kullanılır.

**Söz dizimi :**<br/>
([CancelResponse](/docs/CancelResponse.md)) NestPay::cancel([CancelRequest](/docs/CancelRequest.md) $request)

### refund() :
**refund()** metodu, iade işlemi için kullanılır.

**Söz dizimi :**<br/>
([RefundResponse](/docs/RefundResponse.md)) NestPay::refund([RefundRequest](/docs/RefundRequest.md) $request)

### preAuthorization() :
**preAuthorization()** metodu, ön izin işlemi için kullanılır.

**Söz dizimi :**<br/>
([PreAuthorizationResponse](/docs/PreAuthorizationResponse.md)) NestPay::preAuthorization([PreAuthorizationRequest](/docs/PreAuthorizationRequest.md) $request)

### postAuthorization() :
**postAuthorization()** metodu, ön izin işleminin satışa dönüştürülmesi için kullanılır.

**Söz dizimi :**<br/>
([PostAuthorizationResponse](/docs/PostAuthorizationResponse.md)) NestPay::preAuthorization([PostAuthorizationRequest](/docs/PostAuthorizationRequest.md) $request)
