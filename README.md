# S3-Connectivity-Checker

ECRとS3の疎通を確認します。
タスクとして実行することを想定しています。

## 環境構築

```shell
$ composer install
```

## ローカルでDockerを使用して確認

1. `docker build`でイメージ作成
1. `docker run`でSymfony コマンド実行

### Dockerイメージ作成

```sh
$ docker build -t {{image_name}} .
```

### クレデンシャル準備

```shell
# シェル変数にローカルの`default`のクレデンシャルを設定
AWS_REGION=$(aws configure get region)
AWS_ACCESS_KEY_ID=$(aws configure get aws_access_key_id)
AWS_SECRET_ACCESS_KEY=$(aws configure get aws_secret_access_key)
```

### 保存確認

Symfony Command：sqs:send

```shell
# -eでバケット名とオブジェクト名および↑でシェル変数に格納したクレデンシャルをコンテナに環境変数として渡す
$ docker run -e BUCKET={{バケット名}} \
             -e FILE_NAME={{オブジェクト名}} \
             -e AWS_REGION=$AWS_REGION \
             -e AWS_ACCESS_KEY_ID=$AWS_ACCESS_KEY_ID \
             -e AWS_SECRET_ACCESS_KEY=$AWS_SECRET_ACCESS_KEY \
             {{image name}} bin/console s3:put
````

### 取得確認

Symfony Command：sqs:receive

```shell
# -eでバケット名とオブジェクト名および↑でシェル変数に格納したクレデンシャルをコンテナに環境変数として渡す
$ docker run -e BUCKET={{バケット名}} \
             -e FILE_NAME={{オブジェクト名}} \
             -e AWS_REGION=$AWS_REGION \
             -e AWS_ACCESS_KEY_ID=$AWS_ACCESS_KEY_ID \
             -e AWS_SECRET_ACCESS_KEY=$AWS_SECRET_ACCESS_KEY \
             {{image name}} bin/console s3:get
````

## AWS ECSを使用して確認

1. ECRにリポジトリを作成＆イメージをPush
1. （必要なら）クラスタを作成
1. タスク定義を作成
1. タスクを実行

タスクを実行する際に以下のようにコンテナを上書きします（例:sqs:send）

![コンテナを上書き](aws-ecs.png)