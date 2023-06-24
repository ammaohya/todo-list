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

### 實作lock機制 and 阻擋非鎖定操作者
在todo加上lock_operator_id去辨別todo目前被誰鎖定,並在PUT API新增阻擋,不同operator的話不可操作,且在veiw將操作按鈕隱藏

### 多個操作者鎖定同任務衝突
會加上樂觀鎖機制, 在todo table加上version控制,並加以log去做紀錄
