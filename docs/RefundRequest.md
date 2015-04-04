# RefundRequest

RefundRequest, iade işlemi sırasında gönderilecek parametreleri taşıyan istek nesnesidir. Aşağıdaki tabloda belirtilen alanlara getter/setter metodları vasıtasıyla ulaşılabilir. (Örn: getOrderId() / setOrderId() gibi.)

**İsim Uzayı**:
Paranoia\Payment\Request

**Sınıf Adı:**
RefundRequest

**Dosya :**
/src/Paranoia/Payment/Request/RefundRequest

|---------------|------------|-----------|-------------------------------|
| Alan          | Tip        | Zorunlu ? | Açıklama                      |
|---------------|------------|-----------|-------------------------------|
| OrderId       | String     | Evet      | Sipariş numarası              |
| Amount        | Float      | Evet      | İade edilecek tutar           |
| Currency      | Char(3)    | Evet      | Para birimi                   |
|---------------|------------|-----------|-------------------------------|