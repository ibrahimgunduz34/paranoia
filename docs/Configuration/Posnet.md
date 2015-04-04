# Posnet

Posnet adaptörünün banka ile iletişim kurması sırasında ihtiyaç duyduğu erişim bilgilerini taşıyan konfigürasyon sınıfıdır. Aşağıdaki tabloda belirtilen alanlara getter/setter metodları vasıtasıyla ulaşılabilir. (Örn: getClientId() / setClientId() gibi.)

**İsim Uzayı :**<br/>
Paranoia\Configuration

**Sınıf Adı :**<br/>
Posnet

**Dosya :**<br/>
[/src/Paranoia/Configuration/Posnet.php](/src/Paranoia/Configuration/Posnet.php)

| Alan                  | Tip        | Zorunlu ? | Açıklama
|-----------------------|------------|-----------|---------------------------------|
| ApiUrl				| String | Evet | API isteklerinin gönderileceği URL |
| TerminalId            | Integer    | Evet      | Terminal numarası |
| MerchantId            | Integer    | Evet      | Üye işyeri numarası |