# Web掲示板サービス

テキスト投稿・画像投稿(5MB以下)に対応したシンプルなWeb掲示板です。
nginx + PHP-FPM + MySQLをDocker Composeで構築しています。

## 動作環境

- Amazon Linux 2023 (EC2)
- インバウンドルールでTCP 80番ポートを許可していること(セキュリティグループ設定)

## 構築手順

### 1. Gitのインストール

```
sudo dnf install -y git
```

### 2. リポジトリのclone

```
git clone https://github.com/Zetty213/Web.git
cd Web
```

### 3. Dockerのインストールと起動

```
sudo dnf install -y docker
sudo systemctl enable --now docker
sudo usermod -aG docker ec2-user
```

実行後、一度ログアウトし再度SSH接続してください(グループ設定反映のため)。

### 4. Docker Composeプラグインの導入

Amazon Linux 2023の標準リポジトリには`docker-compose-plugin`パッケージが存在しないため、公式配布バイナリを直接配置します。

```
mkdir -p ~/.docker/cli-plugins
curl -SL https://github.com/docker/compose/releases/latest/download/docker-compose-linux-x86_64 -o ~/.docker/cli-plugins/docker-compose
chmod +x ~/.docker/cli-plugins/docker-compose
docker compose version
```

### 5. buildxプラグインの導入

イメージのビルドに必要です。同様に公式配布バイナリを配置します。

```
mkdir -p ~/.docker/cli-plugins
BUILDX_VERSION=$(curl -s https://api.github.com/repos/docker/buildx/releases/latest | grep -Po '"tag_name": "\K.*?(?=")')
curl -SL "https://github.com/docker/buildx/releases/download/${BUILDX_VERSION}/buildx-${BUILDX_VERSION}.linux-amd64" -o ~/.docker/cli-plugins/docker-buildx
chmod +x ~/.docker/cli-plugins/docker-buildx
docker buildx version
```

### 6. アップロード用ディレクトリの準備

```
mkdir -p src/uploads
chmod -R 777 src/uploads
chmod -R o+rX src
```

### 7. コンテナの起動

```
docker compose up -d --build
docker compose ps
```

nginx / php / db の3コンテナが Up になっていることを確認してください。

### 8. 動作確認

ブラウザで以下にアクセスします。

```
http://<EC2のパブリックIP>/
```

投稿フォームが表示され、テキストと画像を投稿すると一覧に反映されれば構築完了です。

## セキュリティ対策

- SQLインジェクション対策:PDOのプリペアドステートメントを使用
- XSS対策:出力時に`htmlspecialchars()`でエスケープ
- 画像アップロード:サーバー側で5MB以下のバリデーションを実施

## 動作検証

新規EC2インスタンスに対し、本手順書のみに従って構築し、実際に投稿・画像アップロードが動作することを確認済みです。
