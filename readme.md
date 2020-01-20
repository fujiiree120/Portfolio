# 学習背景、目的
 プログラミングスクール学習内容の復習と、個人的に学習したLaravel,Bootstrapの練習を目的として、  
 laravelを使用したECサイトを作成しました。  
 <br>
           
# サイトの説明
プロテインを販売するECサイト  
  サイトURL:http://myapptatsuya.herokuapp.com/  
   ログイン用アカウント  
    ・ログイン用メールアドレス: test@aaa.com  
    ・パスワード:      123456789  
   <br>
   
   ログイン後商品一覧から商品を並び替えができる  
   
   詳細一覧の「商品詳細を見る」から以下の情報を確認できる  
   ・商品の詳細内容  
   ・商品の総合評価(☆1~5)の確認  
   ・ユーザーが投稿したレビューの確認  
   ・ページ下部からレビューを投稿できる  
   
   商品一覧か、商品詳細画面から商品をカートに追加し、  
   ヘッダーのカートボタンからカート内の商品を確認、購入できる
    
   カートページから商品購入後、ヘッダーの購入履歴ボタンから購入した商品の履歴を表示できる  
   
   
# 開発環境
  ・php7.2  
  ・mysql5.7  
  ・Laravel5.5  
  ・VSCode  
  ・git bash    
  ・Docker,Laradockを利用した環境開発  
<br>

# 環境構築方法
　 Dockerのインストール後、git clone https://github.com/LaraDock/laradock.git  
 上記コマンドを実行し、作成されたLaradockディレクトリに移動　　

docker-compose up -d workspaceでワークスペースを立ち上げ動作確認

docker exec -it --user=laradock laradock_workspace_1 bash  
上記コマンドでlaradockにbashでログインする

composer create-project laravel/laravel[プロジェクト名] --prefer-dist 5.5
上記コマンドでバージョン5.5の「プロジェクト」を作成  

laradock環境を一旦停止し、env-exampleファイルを.envファイルとしてコピーし、MYSQLのバージョンを5.7に変更する  

docker-compose up -d workspace nginx mysql phpmyadmin  
上記コマンドで開発環境を立ち上げる  
 
# 使用技術
  ・php  
  ・mysql  
  ・Laravel  
  ・Bootstrap  
  ・Javascript  
  ・CSS  
  ・herokuを利用したサイトの公開  
  ・Docker  
  ・Git  
  ・git bash  

# 工夫した点
  フォームリクエストを利用し、バリデーションの管理をわかりやすくまとめた  
  herokuでは画像生成ができないため、画像データをbase64エンコードしDBに直接格納することで画像表示ができるよう工夫した

 
# スクール受講内容
総受講期間(2019/8/22 ~ 2019/12/25)

## Webプログラミング基礎(2019/8/22 ~ 2019/10/20)
HTML/CSS, JavaScript, PHP5.5  
MySQL5.5  

## 実践開発演習(2019/10/21 ~ 2019/12/25)
開発環境構築(Docker)  
Linux   
Git  
セキュリティ対策(SQLインジェクション, XSS, CSRF)  
VPSを用いた本番環境構築・公開  
スクール最終課題：ECサイト開発実践開発  

# 今回主に学習した習内容
Laravel, Bootstrap  
herokuを利用したwebサイトの公開
