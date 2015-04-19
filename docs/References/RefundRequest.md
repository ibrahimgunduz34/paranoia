# RefundRequest

[İçindekiler](/docs/icindekiler.md) > Referans Klavuzu > İstek Tipleri > RefundRequest

RefundRequest, iade işlemi sırasında gönderilecek parametreleri taşıyan istek nesnesidir. Aşağıdaki tabloda belirtilen alanlara getter/setter metodları vasıtasıyla ulaşılabilir. (Örn: getOrderId() / setOrderId() gibi.)

**İsim Uzayı :**<br/>
Paranoia\Payment\Request

**Sınıf Adı :**<br/>
RefundRequest

**Dosya :**<br/>
[/src/Paranoia/Payment/Request/RefundRequest.php](/src/Paranoia/Payment/Request/RefundRequest.php)

| Alan          | Tip        | Zorunlu ? | Açıklama                      |
|---------------|------------|-----------|-------------------------------|
| OrderId       | String     | Evet      | Sipariş numarası              |
| Amount        | Float      | Evet      | İade edilecek tutar           |
| Currency      | Char(3)    | Evet      | Para birimi                   |
