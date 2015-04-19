# SaleRequest

[İçindekiler](/docs/icindekiler.md) > [Referans Klavuzu](/docs/References.md) > [İstek Tipleri](/docs/References/RequestTypes.md) > SaleRequest

SaleRequest, satış işlemi sırasında gönderilecek parametreleri taşıyan istek nesnesidir. Aşağıdaki tabloda belirtilen alanlara getter/setter metodları vasıtasıyla ulaşılabilir. (Örn: getOrderId() / setOrderId() gibi.)

**İsim Uzayı :**<br/>
Paranoia\Payment\Request

**Sınıf Adı :**<br/>
SaleRequest

**Dosya :**<br/>
[/src/Paranoia/Payment/Request/SaleRequest.php](/src/Paranoia/Payment/Request/SaleRequest.php)

| Alan          | Tip        | Zorunlu ? | Açıklama                      |
|---------------|------------|-----------|-------------------------------|
| OrderId       | String     | Evet      | Sipariş numarası              |
| Amount        | Float      | Evet      | Topam Sipariş tutarı          |
| Currency      | Char(3)    | Evet      | Para birimi                   |
| Installment   | Integer    | Hayır     | Taksit sayısı                 |
| CardNumber    | String     | Evet      | Kart numarası                 |
| SecurityCode  | Char(3)    | Evet      | CVV Kodu                      |
| ExpireMonth   | Integer    | Evet      | Kartın son geçerli olduğu ay  |
| ExpireYear    | Integer    | Evet      | Kartın son geçerli olduğu yıl |
