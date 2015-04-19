# Gvp

[İçindekiler](/docs/icindekiler.md) > Referans Klavuzu > Adaptörler > Gvp

GVP, Garanti Bankası sanal pos alt yapısını kullanan bankalar için geliştirilen adaptör sınıfıdır. GVP sınıfını kullanarak ilgili bankalarda satış, iptal, iade, ön izin ve ön izin işleminin satışa dönüştürülmesi gibi işlemleri gerçekleştirebilirsiniz.

**İsim Uzayı :**<br/>
\Paranoia\Payment\Adapter

**Sınıf Adı :**<br/>
Gvp

**Dosya :**<br/>
/src/Paranoia/Payment/Adapter/Gvp.php

## Metodlar :

### __construct()

**__construct()** metodu, sınıfın kurucu metodudur. İlgili banka API sine ulaşabilmek için kullanılan erişim bilgileri ile donatılmış konfigürasyon sınıfını argüman olarak alır.

**Söz dizimi :**<br/>
(void) Gvp::__construct([\Paranoia\Configuration\Gvp](/docs/References/Configuration/Gvp.md) $configuration)


### sale() :

**sale()** metodu, satış işleminin gerçekleştirilmesi için kullanılır. 

**Söz dizimi :** <br/>
([SaleResponse](/docs/References/ResponseTypes/SaleResponse.md)) Gvp::sale([SaleRequest](/docs/References/RequestTypes/SaleRequest.md) $request)

### cancel() :
**cancel()** metodu, iptal işlemi için kullanılır.

**Söz dizimi :**<br/>
([CancelResponse](/docs/References/ResponseTypes/CancelResponse.md)) Gvp::cancel([CancelRequest](/docs/References/RequestTypes/CancelRequest.md) $request)

### refund() :
**refund()** metodu, iade işlemi için kullanılır.

**Söz dizimi :**<br/>
([RefundResponse](/docs/References/ResponseTypes/RefundResponse.md)) Gvp::refund([RefundRequest](/docs/References/RequestTypes/RefundRequest.md) $request)

### preAuthorization() :
**preAuthorization()** metodu, ön izin işlemi için kullanılır.

**Söz dizimi :**<br/>
([PreAuthorizationResponse](/docs/References/ResponseTypes/PreAuthorizationResponse.md)) Gvp::preAuthorization([PreAuthorizationRequest](/docs/References/RequestTypes/PreAuthorizationRequest.md) $request)

### postAuthorization() :
**postAuthorization()** metodu, ön izin işleminin satışa dönüştürülmesi için kullanılır.

**Söz dizimi :**<br/>
([PostAuthorizationResponse](/docs/References/ResponseTypes/PostAuthorizationResponse.md)) Gvp::preAuthorization([PostAuthorizationRequest](/docs/References/RequestTypes/PostAuthorizationRequest.md) $request)
