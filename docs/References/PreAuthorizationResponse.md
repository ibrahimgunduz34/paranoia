# PreAuthorizationResponse

[İçindekiler](/docs/icindekiler.md) > Referans Klavuzu > Yanıt Tipleri > PreAuthorizationResponse

PreAuthorizationResponse, ön izin işlemi sonucunda dönen cevaba ilişkin  parametreleri taşıyan yanıt nesnesidir. Aşağıdaki tabloda belirtilen **isSuccess** dışındaki tüm alanlara getter/setter metodları vasıtasıyla ulaşılabilir. (Örn: getOrderId() / setOrderId() gibi.) 

**İsim Uzayı :**<br/>
Paranoia\Payment\Response

**Sınıf Adı :**<br/>
PreAuthorizationResponse

**Dosya :**<br/>
[/src/Paranoia/Payment/Response/PreAuthorizationResponse.php](/src/Paranoia/Payment/Response/PreAuthorizationResponse.php)

| Alan          | Tip        | Açıklama                                   |
|---------------|------------|--------------------------------------------|
| isSuccess     | Boolean    | İşlemin başarılı olma durumunu gösterir.   |
| Code 			| String	 |vap kodu bilgisini içerir.                  |
| Message		| String     | Cevaba ile ilgili mesaj bilgisini içerir.  |
| OrderId       | String     | Sipariş numarası                           |
| TransactionId | String     | Banka tarafından üretilen hareket numarası |
