# PostAuthorizationRequest

[İçindekiler](/docs/icindekiler.md) > Referans Klavuzu > [İstek Tipleri](/docs/References/RequestTypes.md) > PostAuthorizationRequest

PostAuthorizationRequest, ön izin işleminin satışa dönüştürülmesi işlemi sırasında gönderilecek parametreleri taşıyan istek nesnesidir. Aşağıdaki tabloda belirtilen alanlara getter/setter metodları vasıtasıyla ulaşılabilir. (Örn: getOrderId() / setOrderId() gibi.)

**İsim Uzayı :**<br/>
Paranoia\Payment\Request

**Sınıf Adı :**<br/>
PostAuthorizationRequest

**Dosya :**<br/>
[/src/Paranoia/Payment/Request/PostAuthorizationRequest.php](/src/Paranoia/Payment/Response/PostAuthorizationResponse.php)

| Alan          | Tip        | Zorunlu ? | Açıklama                      |
|---------------|------------|-----------|-------------------------------|
| OrderId       | String     | Evet      | Sipariş numarası              |
