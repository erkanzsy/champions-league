# champions-league


## API Request ve Response Örneği

Aşağıda, API kullanarak yapılacak bir istek ve elde edilen bir örnek yanıtın nasıl görünebileceğiyle ilgili bir örnek bulunmaktadır:

### İstek

```http
GET /fixture HTTP/1.1
Host: 127.0.0.1:8000
Accept: application/json

HTTP/1.1 200 OK
Content-Type: application/json
{
  "status": "success",
  "data": {
    "league": {
    ""Premier League"
    },
    "": "John Doe",
    "email": "john@example.com"
  }
}
```



### Reset

```http
GET /reset HTTP/1.1
Host: 127.0.0.1:8000
Accept: application/json

HTTP/1.1 200 OK
Content-Type: application/json
{
  "status": "success",
  "data": {
    "league": {
    ""Premier League"
    },
    "": "John Doe",
    "email": "john@example.com"
  }
}
```

