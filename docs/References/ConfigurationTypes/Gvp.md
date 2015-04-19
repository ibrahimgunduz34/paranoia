# Gvp

[İçindekiler](/docs/icindekiler.md) > Referans Klavuzu > [Konfigürasyon Tipleri](/docs/References/ConfigurationTypes.md) > Gvp

Gvp adaptörünün banka ile iletişim kurması sırasında ihtiyaç duyduğu erişim bilgilerini taşıyan konfigürasyon sınıfıdır. Aşağıdaki tabloda belirtilen alanlara getter/setter metodları vasıtasıyla ulaşılabilir. (Örn: getClientId() / setClientId() gibi.)

**İsim Uzayı :**<br/>
Paranoia\Configuration

**Sınıf Adı :**<br/>
Gvp

**Dosya :**<br/>
[/src/Paranoia/Configuration/Gvp.php](/src/Paranoia/Configuration/Gvp.php)

| Alan                  | Tip        | Zorunlu ? | Açıklama
|-----------------------|------------|-----------|---------------------------------|
| ApiUrl				| String | Evet | API isteklerinin gönderileceği URL |
| TerminalId            | Integer    | Evet      | Terminal numarası |
| MerchantId            | Integer    | Evet      | Üye işyeri numarası |
| AuthorizationUsername | String     | Evet      | Staış, ön izin ve ön izinin satışa dönüştürülmesi sırasında kullanılacak kullanıcı adı |
| AuthorizationPassword | String     | Evet      | Staış, ön izin ve ön izinin satışa dönüştürülmesi sırasında kullanılacak kullanıcı parolası |
| RefundUsername        | String     | Evet      | İptal ve iade sırasında kullanılacak kullanıcı adı |
| RefundPassword        | String     | Evet      | İptal ve iade sırasında kullanılacak kullanıcı parolası |
| Mode                  | String     | Evet      | API erişim modu (TEST/PROD) |



