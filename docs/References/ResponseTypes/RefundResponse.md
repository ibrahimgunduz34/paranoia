# RefundResponse

[İçindekiler](/docs/icindekiler.md) > Referans Klavuzu > [Yanıt Tipleri](/docs/References/ResponseTypes.md) > RefundResponse

RefundResponse, iade işlemi sonucunda dönen cevaba ilişkin  parametreleri taşıyan yanıt nesnesidir. Aşağıdaki tabloda belirtilen **isSuccess** dışındaki tüm alanlara getter/setter metodları vasıtasıyla ulaşılabilir. (Örn: getOrderId() / setOrderId() gibi.) 

**İsim Uzayı :**<br/>
Paranoia\Payment\Response

**Sınıf Adı :**<br/>
RefundResponse

**Dosya :**<br/>
[/src/Paranoia/Payment/Response/RefundResponse.php](/src/Paranoia/Payment/Response/RefundResponse.php)

| Alan          | Tip        | Açıklama                                   |
|---------------|------------|--------------------------------------------|
| isSuccess     | Boolean    | İşlemin başarılı olma durumunu gösterir.   |
| Code 			| String	 |vap kodu bilgisini içerir.                  |
| Message		| String     | Cevaba ile ilgili mesaj bilgisini içerir.  |
| TransactionId | String     | Banka tarafından üretilen hareket numarası |
