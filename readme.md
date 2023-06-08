## Todo List

使用的是Laravel5.7, 並且使用docker-compose

### docker ontainer run
```
docker-compose up -d --build
```

### db migrate
```
docker exec -it rodo_list_php php artisan migrate
```

### 打開畫面
```
http://{your ip}
```

### backend api
```
GET http://{your ip}/api/todos
POST http://{your ip}/api/todo/{id}
PUT http://{your ip}/api/todo/{id}
DELETE http://{your ip}/api/todo/{id}
```

### log 權限問題
```
sudo chmod o+w ./storage/ -R
```


