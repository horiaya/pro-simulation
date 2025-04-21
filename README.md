<img width="1438" alt="スクリーンショット 2025-04-02 15 04 53" src="https://github.com/user-attachments/assets/be30e8a6-742e-43f6-ae8b-3c53b6115180" />

# COACHTECHフリマアプリ
企業が開発した独自のフリマアプリを開発

## アプリケーションURL
デプロイは未実施のため、ローカル環境で実行してください

## 機能一覧
ログイン機能・購入機能・出品機能・お気に入り登録・プロフィール設定

## 使用技術（ローカル環境）
バージョン：Laravel Framework 8.83.29

- PHP（Laravel）
- HTML, CSS
- JavaScript
- Composer
- Docker

データベース：MySQL

メール認証：MailHog

決済機能：Stripe / Webhook / ngrok

## テーブル設計
[テーブル設計](https://docs.google.com/spreadsheets/d/1OD4KdAFMQVUMECPXe2c-7Nr6lRiEDCsaDZ6IcZsRwOk/edit?gid=1188247583#gid=1188247583)

## ER図
[ER図](https://github.com/user-attachments/assets/c5c6bbcd-0554-4fd1-81db-cf439270db4b)

## 環境構築
### １、リポジトリのクローン
```sh
git clone https://github.com/horiaya/pro-simulation.git
cd pro-simulation
```

### 2、.envの設定
```sh
cp .env.example .env
```
### ３、DBの設定
```sh
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```

### ４、keyの作成
php artisan key:generate

### ５、DBをmigrateする
php artisan migrate

### ６、起動
php artisan serve

### ７、アクセス
http://localhost:80/login

### その他

## メール認証の.env設定(MailHog)
```sh
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="test@example.com"
MAIL_FROM_NAME=laravel.app
```

## 決済機能（Stripe）
[Stripe](https://stripe.com/jp) のサンドボックス（テスト環境）を利用して決済機能を実装しています。

### 決済の流れ
1. 商品を選択し、「購入」ボタンをクリック  
2. Stripeのテスト決済画面でカード情報を入力  
3. 決済後、購入完了画面に遷移

### Webhook設定について（開発用）

StripeのWebhookをローカルで受け取るために、[ngrok](https://ngrok.com/) を使用しています。

以下のようにngrokを起動して、StripeのWebhookに登録してください：

```bash
ngrok http 80
```

### テスト用カード情報

テスト決済を行う際は、以下のカード情報をご使用ください：

- カード番号：4242 4242 4242 4242  
- 有効期限：任意の未来の日付（例：12/34）  
- セキュリティコード（CVC）：任意の3桁（例：123）  


### テスト用ユーザー

- ユーザー名：　テストa
- メールアドレス：　aaa@aaa.com
- パスワード：　aaaa1234
