# NestPay

[İçindekiler](/docs/icindekiler.md) > Referans Klavuzu > [Konfigürasyon Tipleri](/docs/References/ConfigurationTypes.md) > NestPay

NestPay adaptörünün banka ile iletişim kurması sırasında ihtiyaç duyduğu erişim bilgilerini taşıyan konfigürasyon sınıfıdır. Aşağıdaki tabloda belirtilen alanlara getter/setter metodları vasıtasıyla ulaşılabilir. (Örn: getClientId() / setClientId() gibi.)

**İsim Uzayı :**<br/>
Paranoia\Configuration

**Sınıf Adı :**<br/>
NestPay

**Dosya :**<br/>
[/src/Paranoia/Configuration/NestPay.php](/src/Paranoia/Configuration/NestPay.php)

| Alan                  | Tip        | Zorunlu ? | Açıklama
|-----------------------|------------|-----------|---------------------------------|
| ApiUrl				| String | Evet | API isteklerinin gönderileceği URL |
| ClientId              | Integer    | Evet      | Üye işyeri numarası |
| Username				| String | Evet | Kullanıcı adı |
| Password | String | Evet | Kullanıcı parolası |
| Mode                  | String     | Evet      | API erişim modu (TEST/PROD) |