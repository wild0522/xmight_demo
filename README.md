## 建立環境 (for Mac)
```bash
$ git https://github.com/wild0522/xmight_demo.git
$ docker-compose up -d
$ docker-compose exec --user=dock lemp bash
lara$ cd /var/www/house
lara$ npm run init
lara$ exit
$ sudo -s
bash$ (echo "127.0.0.1 house.dev";echo "127.0.0.1 dashboard.dev") >> /etc/hosts```
bash$ exit
```

## Demo 操作
- 瀏覽器 house.dev
  1. 瀏覽器登入 http://house.dev 註冊身份
  1. 點擊 Click me! 連結，會導向 dashboard，並註冊 Api token
- 瀏覽器 dashboard.dev
  1. 建立 channel， ex. ch1, ch2
  1. 點擊 Random 隨機產 item data，並開啟該 channel 線圖
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
