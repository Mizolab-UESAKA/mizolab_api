# 開発環境の構築
WindowsでDockerを使ってローカル環境を構築する方法について説明します。

※MacはWSLの部分を飛ばして作業すれば構築可能なはずです。

<br>
<br>
<br>

## ミドルウェア

* Laravel 8.x (PHP8.1, Composer, cron, supervisor) 
* MySQL 8.0
* Minio (latest - AWS S3)
* MailHog (SMTP Server)

<br>
<br>
<br>

## 開発環境前提条件
* WindowsOS / WSL2が使えるwindows10
  ( [Windows Subsystem for Linux 2](https://docs.microsoft.com/ja-jp/windows/wsl/install-win10) )
* [GitHub](https://github.com/) への公開鍵の登録
* [Docker Desktop for Windows](https://docs.docker.com/desktop/windows/install/) をインスール済み
* [Gitコマンド](https://git-scm.com/) をインストール済み
* [GitBash](https://gitforwindows.org/) ※インストールは任意です。

<br>
<br>
<br>

## 開発環境前提条件の準備
全体的には以下の記事を参考にしてください。

[Windows10におけるLaravel Sailの最適な開発環境の作り方（WSL2 on Docker）](https://zenn.dev/goro/articles/018e05bee92aa1)

### 【Windows】WSL2インストール
WSL2をインストールします。
[WSL のインストール](https://docs.microsoft.com/ja-jp/windows/wsl/install)

このコマンドをpowershellで実行するだけでいいようです。

```powershell
wsl --install
```

### 【Windows】Docker Desktop for Windowsインストール
Docker Desktop for Windows をインストールします。
[Docker Desktop for Windows](https://docs.docker.com/desktop/windows/install/) 

### 【Mac】Docker Desktop for Macインストール
[Docker Desktop for Mac](https://docs.docker.jp/desktop/install/mac-install.html)

### 公開鍵・秘密鍵の生成とGitHubへの登録
公開鍵・秘密鍵の生成とGitHubへの登録を行います。

[GitやGitHubでSSHに接続する方法をわかりやすく解説！](https://www.sejuku.net/blog/74220)

※Enter passphraseは何も指定せずEnterでOK
※うまくいかない場合は以下も参照
[GitHubでssh接続する手順~公開鍵・秘密鍵の生成から](https://qiita.com/shizuma/items/2b2f873a0034839e47ce)

### ssh設定をWSL内にコピーする
Ubuntu 20.04 on Windows を起動し、Ubuntuターミナル内で作業します。
以下記事の「rsyncやcronなどの面倒なことをせず単純に...」の部分のコマンドを実行します。

[Windowsのssh設定をWindows Subsystem for Linux（WSL）に適用する](https://qiita.com/yumenomatayume/items/552b1a4406e85df306c4)

※WSL内でcloneするためWindows側のssh設定をWSL内にコピーします。

<br>
<br>
<br>

## ソースコードの配置
Ubuntu 20.04 on Windows を起動し、Ubuntuターミナル内で作業します。

※Windowsのローカルフォルダにソースコードを配置し、実行はWSL内で行っているとファイルシステムの差異から段々動作が重くなってきます。

※これを回避するためWSL内にソースコードを配置し、Windows側のVSCodeからWSL内を参照して開発を行います。
※Macの場合はそのままローカルストレージに配置してください。

作業ディレクトリを作成します。

```bash:wsl
$ mkdir -p ~/workspace
$ cd workspace
$ mkdir -p uzone_api
$ cd uzone_api
```

ソースコードを配置します。

```
$ git clone git@github.com:Partsone/uzone_api.git .
$ git checkout develop
```

<br>
<br>
<br>

## 環境設定

環境設定ファイルを作成します。

```bash:wsl
$ cp ./src/.env.example ./src/.env
```

※ローカルでの開発の場合、上記のままでOKです。必要に応じて値を変更してください。

## Dockerイメージ作成
Docker Desk Topを起動します。
Dockerイメージを作成します。

※php,mysql,node環境のコマンドをラッピングできるため、Laravel公式で提供されているsailコマンドを流用しています。

※内部的にはdocker-composeコマンドを使用しています。詳細はプロジェクトディレクトリ直下に配置している`sail`ファイルを参照してください。

```bash:wsl
./sail build
```

エラーになる場合は以下を試してください。
```bash:wsl
./sail build --no-cache
```

## Dockerコンテナの起動
dockerコンテナを起動します。

```bash:wsl
./sail up -d
```

<br>
<br>
<br>

## Composerインストール
Composerで各種ライブラリをコンテナにインストールします。
その後コンテナを再起動します。

```bash:wsl
./sail composer install
./sail down
./sail up -d
```

### メール関連の設定

SendGrid関連の環境変数を設定します。
ローカルでのメールサーバはmailhogも利用可能ですが、メールテンプレートをsendgrid側に登録しているため、ローカル確認でもsend gridを利用します。

以下を有効にします。

```env
## send grid利用の場合
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=uzone
SENDGRID_API_KEY=xxxxx
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=info@uzone.com
MAIL_FROM_NAME="${APP_NAME}"
```

### アカウント情報

APIキーなどは以下を参照してください。

[アカウント情報(権限注意)](https://docs.google.com/spreadsheets/d/1wiSH9I02BKW7sEpcqmgGiMbhFt32cIvhGFCR-kXlYXs/edit#gid=0)

<br>
<br>
<br>

## マイグレーションとデータ作成
### マイグレーション
データベースを作成します。

※既存のテーブルは全て破棄して再作成されます

```bash:wsl
./sail artisan migrate:fresh
```

### laravel passport インストール
認証ライブラリをインストールします。
UZoneではPersonalAccessTokenを使用します。
以下のコマンドを実行すると *「Personal access client created successfully.」の下のIDとシークレットが表示されるのでメモします。* 

```bash:wsl
./sail artisan passport:install --force

Personal access client created successfully.
Client ID: 1 // ←これ
Client secret: L3D7dJDTXok8wxpbMjKWdNxeuxmULaDbWgbBhAg5 // ←これ

Password grant client created successfully.
Client ID: 2
Client secret: OqzpCLRqz3sLy9uWJOYk5xSau8BAcXmJtQtQS1jG
```

<br>
<br>
<br>

### Laravel　passport関連の設定
/src/.envファイルに環境変数を設定します。
上記で取得したLaravel passportのpersonal access clientの値を設定します。

```config:.env
PASSPORT_PERSONAL_ACCESS_CLIENT_ID=[上記で取得したClient ID]
PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET=[上記で取得したClient secret]
```

### マスタデータ作成
初期マスタデータを作成します。

※本番稼働時も必要なデータです。

```bash
./sail artisan db:seed
./sail artisan db:seed --class=InitialSeeder
```

### 検索系マスタデータ作成
検索系マスタデータを作成します。検索ロジックで利用します。

※本番稼働時も必要なデータです。

※ローカル環境構築時は必要に応じて作成してください。

※telescopeのエラーが発生する場合は.envの[telescope]欄の設定を全てfalseにしてください。

※コンテナのメモリエラーが発生する場合は、当Readmeの【docker設定】欄を参考にDocker設定を見直してください。


```bash
## 車両系マスタ用のワークテーブル作成。
./sail artisan db:seed --class=CarInfoBaseDataSeeder
## 車両系マスタ共通データ作成。
./sail artisan db:seed --class=CarsInitDataSeeder
## 検索系マスタ各種メーカーデータ作成。(データ量が多いため検索ロジック関連の確認をしなければ実行不要)
./sail artisan db:seed --class=MakerInfoDataSeeder
################################
## メーカーごとに個別実行する場合(一気に全量流すとDBが止まる場合やエラーが発生する場合の暫定対応)
################################
./sail artisan db:seed --class=Database\\Seeders\\ToyotaInitDataSeeder
./sail artisan db:seed --class=Database\\Seeders\\NissanInitDataSeeder
./sail artisan db:seed --class=Database\\Seeders\\MitsubishiInitDataSeeder
./sail artisan db:seed --class=Database\\Seeders\\MazdaInitDataSeeder
./sail artisan db:seed --class=Database\\Seeders\\SuzukiInitDataSeeder
./sail artisan db:seed --class=Database\\Seeders\\HondaInitDataSeeder
./sail artisan db:seed --class=Database\\Seeders\\DaihatsuInitDataSeeder
./sail artisan db:seed --class=Database\\Seeders\\SubaruInitDataSeeder

```

### トランデータ（商品データ）作成
以下のinsert文をツールなどからローカルのデータベースにinsertして商品データを登録します。<br>
※本番稼働時はATRSまたはリビルト品メーカーからシステム連携されるため不要なサンプルデータです。

[20230223_itemsテーブルinsert文 ](https://uzone.backlog.com/alias/file/33056362)

### 管理画面用サンプルデータ作成
管理画面用サンプルデータを作成します。

※本番稼働時は不要なサンプルデータです。

```bash:wsl
./sail artisan db:seed --class=SampleSeeder
```

<br>
<br>
<br>

## フロントエンドのローカル環境構築
フロントエンドのローカル環境構築は以下ReadMeを参考にしてください。

[uzone_admin_web](https://github.com/Partsone/uzone_admin_web)

<br>
<br>
<br>

## 動作確認
ブラウザからのアクセスを確認してみる。

```bash:wsl
# UZoneアドミン画面
http://localhost

※APIでは画面は使わないですが動作確認のため。
※　UZONE API　が表示されればOK

# API動作確認(Swagger)
http://localhost:8002

# MinIO (仮想環境 - AWS S3)
http://localhost:8900
ID : sail
PW : password

# Mailhog (仮想環境 - メールサーバ)
http://localhost:8025

# ログイン画面(フロントエンド)
http://localhost:3000
```

<br>
<br>
<br>


**ここまでで環境構築は完了です。**

以降は開発時の操作コマンドです。

## 設計・開発時の手順
設計・開発時の手順は以下ドキュメントを参考にしてください。<br>

[設計開発手順 README-Development](https://github.com/Partsone/uzone_api/blob/develop/doc/README-Development.md)

## WSL内に置いたソースコードをVSCodeで参照する（任意）
Windowsでの開発用時のパフォーマンス対策として、WSL内にソースコードを配置している。
しかし開発時のエディタはVScodeを使いたいため、Windows側のVSCodeからWSL内を参照する。
また、毎回WSL内のパスを登録しておくためProject Managerという拡張機能を利用する。

参考記事
[Vscodeの拡張機能「Project Manager」の使い方 - 複数のフォルダを同時に開く](https://yumegori.com/vscode-project-manager20191129)

※WSL内のフォルダは以下のように指定すると開けます。エクスプローラーからも見えます。

例）

```
\\wsl$\Ubuntu-20.04\home\manabu\workspace\uzone-admin-api
```


<br>
<br>
<br>

## PHP(Laravel)サーバにSSH接続する
Laravelが起動しているdockerコンテナへsailユーザでSSH接続します。

```bash:wsl
./sail shell
```

Laravelが起動しているdockerコンテナへrootユーザでSSH接続します。

```bash:wsl
./sail root-shell
```

<br>
<br>
<br>

## PHP(Laravel)サーバのログを確認する
PHP(Laravel)サーバのdockerコンテナのログを確認します。

```bash:wsl
./sail logs
./sail logs laravel.test
./sail logs -f laravel.test
```

<br>
<br>
<br>

## サーバを破棄する
PHP(Laravel)サーバのdockerコンテナを破棄します。
永続化しているMySQLのデータは消えません。

```bash:wsl
./sail down
```

永続化しているMySQLのデータ (Volume)も一緒に削除します。
※ 注意： MySQLのデータも削除されます

```bash:wsl
./sail down -v
```

<br>
<br>
<br>

## データベース動作確認
[HeidiSQL](https://forest.watch.impress.co.jp/library/software/heidisql/) などのソフトでアクセスしてみる。

```
Host: localhost  (127.0.0.1)
User: uzone
Password: password
Database: uzone
Port: 3306
```

<br>
<br>
<br>

## Composer操作
ComposerでPHPライブラリを追加/更新/削除するコマンドです。

```bash:wsl
./sail composer install
./sail composer update
./sail composer require <package>[:<tag>]
./sail composer remove <package>
./sail composer dump-autoload
```

<br>
<br>
<br>

## Laravelコンテナ内のvendorフォルダをローカルストレージに同期
vendorフォルダはディスクI/Oの問題でローカルストレージと同期を取っていません。
ソース参照などのでdockerコンテナからローカルストレージにコピーしたい場合は下記コマンドを実行してください。
※コピーしたローカルストレージのvendorフォルダ内ソースを変更してもdockerコンテナ内のLaravelには影響しません。

```bash
cd ~/workspace/uzone_api
docker cp $(docker-compose ps -q laravel.test):/var/www/html/vendor ./src/
```


## docker設定

検索データ投入はデータ量が多いため、Dockerのメモリエラーが発生する場合があります。

その場合、Dockerのメモリとスワップを増やしてみてください。

[参考]
memory : 4GB
Swap   : 4GB

**設定変更方法**
https://techtechmedia.com/docker-trouble-solution/#:~:text=%E3%83%A1%E3%83%A2%E3%83%AA%E3%82%92%E5%A2%97%E3%82%84%E3%81%99%E6%96%B9%E6%B3%95%E3%81%AF,%E5%BC%95%E3%81%8D%E4%B8%8A%E3%81%92%E3%82%8B%E3%81%A0%E3%81%91%E3%81%A7%E5%8F%AF%E8%83%BD%E3%81%A7%E3%81%99%E3%80%82
