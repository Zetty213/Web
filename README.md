cd ~/bbs-project
cat > README.md << 'EOF'
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

### 4. Docker Composeプラグインのインストール

```
sudo dnf install -y docker-compose-plugin
```

### 5. アップロード用ディレクトリの準備

```
mkdir -p src/uploads
chmod -R 777 src/uploads
chmod -R o+rX src
```

### 6. コンテナの起動

```
docker compose up -d --build
docker compose ps
```

nginx / php / db の3コンテナが Up になっていることを確認してください。

### 7. 動作確認

ブラウザで以下にアクセスします。

```
http://<EC2のパブリックIP>/
```

投稿フォームが表示され、テキストと画像を投稿すると一覧に反映されれば構築完了です。

## セキュリティ対策

- SQLインジェクション対策:PDOのプリペアドステートメントを使用
- XSS対策:出力時に`htmlspecialchars()`でエスケープ
- 画像アップロード:サーバー側で5MB以下のバリデーションを実施
EOF
git add README.md
git commit -m "Add setup instructions"
git push
