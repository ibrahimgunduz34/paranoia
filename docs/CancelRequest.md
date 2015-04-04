# CancelRequest

CancelRequest, iptal işlemi sırasında gönderilecek parametreleri taşıyan istek nesnesidir. Aşağıdaki tabloda belirtilen alanlara getter/setter metodları vasıtasıyla ulaşılabilir. (Örn: getOrderId() / setOrderId() gibi.)

**İsim Uzayı**:
Paranoia\Payment\Request

**Sınıf Adı:**
CancelRequest

**Dosya :**
/src/Paranoia/Payment/Request/CancelRequest

|---------------|------------|-----------|--------------------------------------------|
| Alan          | Tip        | Zorunlu ? | Açıklama                                   |
|---------------|------------|-----------|--------------------------------------------|
| OrderId       | String     | Evet      | Sipariş numarası                           |
| TransactionId | String     | Evet      | Banka tarafından üretilen hareket numarası |
|---------------|------------|-----------|--------------------------------------------|

**Not :**
İptal işlemi sırasında OrderId veya TransactionId alanlarından **sadece** biri kullanılabilir. Aynı siparişe ait kısmi iade işlemlerinde TransactionId tercih edilmelidir. TransactionId bilgisi satış veya satışa dönüştirilen ön izin işlemleri sonrasında elde edilen yanıt tiplerinde (Bkz. SaleResponse, PostAuthorizationResponse) yeralmaktadır.

