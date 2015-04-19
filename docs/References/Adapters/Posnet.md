# Posnet

[İçindekiler](/docs/icindekiler.md) > Referans Klavuzu > [Adaptörler](/docs/References/Adapters.md) > Posnet

Posnet, YapıKredi Bankası sanal pos alt yapısını kullanan bankalar için geliştirilen adaptör sınıfıdır. Posnet sınıfını kullanarak ilgili bankalarda satış, iptal, iade, ön izin ve ön izin işleminin satışa dönüştürülmesi gibi işlemleri gerçekleştirebilirsiniz.

**İsim Uzayı :**<br/>
\Paranoia\Payment\Adapter

**Sınıf Adı :**<br/>
Posnet

**Dosya :**<br/>
/src/Paranoia/Payment/Adapter/Posnet.php

## Metodlar :

### __construct()

**__construct()** metodu, sınıfın kurucu metodudur. İlgili banka API sine ulaşabilmek için kullanılan erişim bilgileri ile donatılmış konfigürasyon sınıfını argüman olarak alır.

**Söz dizimi :**<br/>
(void) Posnet::__construct([\Paranoia\Configuration\Posnet](/docs/References/ConfigurationTypes/Posnet.md) $configuration)


### sale() :

**sale()** metodu, satış işleminin gerçekleştirilmesi için kullanılır. 

**Söz dizimi :** <br/>
([SaleResponse](/docs/References/ResponseTypes/SaleResponse.md)) Posnet::sale([SaleRequest](/docs/References/RequestTypes/SaleRequest.md) $request)

### cancel() :
**cancel()** metodu, iptal işlemi için kullanılır.

**Söz dizimi :**<br/>
([CancelResponse](/docs/References/ResponseTypes/CancelResponse.md)) Posnet::cancel([CancelRequest](/docs/References/RequestTypes/CancelRequest.md) $request)

### refund() :
**refund()** metodu, iade işlemi için kullanılır.

**Söz dizimi :**<br/>
([RefundResponse](/docs/References/ResponseTypes/RefundResponse.md)) Posnet::refund([RefundRequest](/docs/References/RequestTypes/RefundRequest.md) $request)

### preAuthorization() :
**preAuthorization()** metodu, ön izin işlemi için kullanılır.

**Söz dizimi :**<br/>
([PreAuthorizationResponse](/docs/References/ResponseTypes/PreAuthorizationResponse.md)) Posnet::preAuthorization([PreAuthorizationRequest](/docs/References/RequestTypes/PreAuthorizationRequest.md) $request)

### postAuthorization() :
**postAuthorization()** metodu, ön izin işleminin satışa dönüştürülmesi için kullanılır.

**Söz dizimi :**<br/>
([PostAuthorizationResponse](/docs/References/ResponseTypes/PostAuthorizationResponse.md)) Posnet::preAuthorization([PostAuthorizationRequest](/docs/References/RequestTypes/PostAuthorizationRequest.md) $request)
