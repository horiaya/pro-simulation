<img width="1438" alt="スクリーンショット 2025-04-02 15 04 53" src="https://github.com/user-attachments/assets/be30e8a6-742e-43f6-ae8b-3c53b6115180" />

# COACHTECHフリマアプリ
企業が開発した独自のフリマアプリを開発

## アプリケーションURL
デプロイは未実施のため、ローカル環境で実行してください

## 機能一覧
ログイン機能・購入機能・出品機能・お気に入り登録・プロフィール設定・取引チャット機能・レビュー機能

## 使用技術（ローカル環境）
バージョン：Laravel Framework 8.83.29

- PHP（Laravel）
- HTML, CSS
- JavaScript
- Composer
- Docker

データベース：MySQL

メール認証：MailHog

決済機能：Stripe / Webhook 

## テーブル設計
[テーブル設計](https://docs.google.com/spreadsheets/d/1OD4KdAFMQVUMECPXe2c-7Nr6lRiEDCsaDZ6IcZsRwOk/edit?gid=1188247583#gid=1188247583)

## ER図
[ER図](https://github.com/user-attachments/assets/c5c6bbcd-0554-4fd1-81db-cf439270db4b)

## 環境構築
このプロジェクトは Laravel 本体が `src/` ディレクトリにあります。
Docker構成で /src は /var/www にマウントされています。
nginx 経由でポート 80 番（http://localhost）からアクセスできます。

php artisan serve の使用は不要です（nginxが処理しているため）。

以下の手順で Laravel の開発環境を起動できます

---

### １、リポジトリのクローン
```sh
git clone https://github.com/horiaya/pro-simulation.git
cd pro-simulation
```

### 2、.envの設定
```sh
cd src
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

### ４、Docker コンテナの起動（ホスト側）
```sh
cd ..
docker compose up -d
```

### ５、PHP コンテナに入り、Laravelのセットアップ（コンテナ内）
```sh
docker compose exec php bash
cd /var/www

# 依存パッケージをインストール（初回のみ）
composer install

# アプリケーションキーの生成
php artisan key:generate

# マイグレーションの実行（初回のみ）
php artisan migrate

#　シンボルリンクの作成
php artisan storage:link
```

### 6、アクセス
http://localhost/login

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

ローカル環境からStripeに接続するためにStripe CLIを使用します。

### 決済の流れ
1. 商品を選択し、「購入」ボタンをクリック  
2. Stripeのテスト決済画面でカード情報を入力  
3. 決済後、購入完了画面に遷移

### 

### Webhook設定について（開発用）
.envファイルに下記を追加し、APIキーと署名シークレットを設定してください。

STRIPE_SECRET=

STRIPE_PUBLIC_KEY=

STRIPE_WEBHOOK_SECRET=

```sh
docker run -it --rm \
  --network pro-simulation_app-network \
  stripe/stripe-cli:latest \
  listen --api-key sk_test_XXXXXXXX \
  --events checkout.session.completed,payment_intent.succeeded \
  --forward-to http://nginx:80/api/webhook/stripe
```
（　sk_test_XXXXXXXX はAPIシークレットキーです。）

そして、起動直後に表示される whsec_... を .env の STRIPE_WEBHOOK_SECRET に設定します。

起動させたまま実行することで決済が完了されます。


### テスト用カード情報

テスト決済を行う際は、以下のカード情報をご使用ください：

- カード番号：4242 4242 4242 4242  
- 有効期限：任意の未来の日付（例：12/34）  
- セキュリティコード（CVC）：任意の3桁（例：123）

### 取引チャット機能
- 商品の購入が完了後、取引中の商品に追加されます。
- 出品者は購入者からメッセージなければ追加されないようにしています。
- 購入者も出品者もメッセージと画像が送信できます。

### レビュー機能
- 取引完了後、レビュー画面が表示されます。
- 評価したい数だけ星のアイコンをクリックすると評価をつけられます。
- 評価されたユーザーはマイページにて平均されたレビュー数が色付けされます。

### テスト用ユーザー

- ユーザー名：　テストa
- メールアドレス：　aaa@aaa.com
- パスワード：　aaaa1234
ダミーユーザは上記含めて3人分をUserTableSeederで用意してます。
