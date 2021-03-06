## 建立環境 (for Mac)
```bash
$ git clone https://github.com/wild0522/xmight_demo.git
$ cd xmight_demo
$ docker-compose up -d
$ docker-compose exec --user=dock lemp bash
dock$ cd /var/www/house;npm run init;ch /var/www/dashboard;npm run init; #約需要20分鐘
dock$ exit
$ sudo -s
bash$ (echo "127.0.0.1 house.dev";echo "127.0.0.1 dashboard.dev") >> /etc/hosts
bash$ exit
```
- 速度太慢可使用此指令最佳化(in container)
```bash
lara$ cd /var/www/house;npm run faster;ch /var/www/dashboard;npm run faster;
```

## Demo 操作
- 瀏覽器 house.dev
  1. 瀏覽器登入 http://house.dev 註冊身份
  1. 點擊 Click me! 連結，會導向 http://dashboard.dev ，並註冊 Api token
- 瀏覽器 dashboard.dev
  1. 建立 channel， ex. ch1, ch2
  1. 點擊 Random (隨機產 item data + 顯示 channel 線圖)
- 直接用 RESTful Tool 測試
  1. 登入 house.dev
  1. 將 header 加入 Authorization:Bearer xxx
  1. API
    - POST   http://house.dev/api/producer/channel
    - PATCH  http://house.dev/api/producer/channel/{id}
    - DELETE http://house.dev/api/producer/channel/{id}
    - GET    http://house.dev/api/producer/channel
    - GET    http://house.dev/api/producer/channel/{id}
    - POST   http://house.dev/api/producer/channel/{id}/item
    - GET    http://house.dev/api/consumer/channel/{id}/{from}/{to} #timestamp 秒

## 刪除環境
```bash
$ #到 xmight_demo 根目錄
$ docker-compose down
$ rm -r ../xmight_demo
$ sudo -s
bash$ nano /etc/hosts  #將最後兩行刪除
bash$ exit
```
