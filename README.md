# Laravel5 Rest API サンプル 

Laravel 5.4系のRest APIサンプル　　　　

## 概要

Laravelでユーザー登録、購入情報管理を行うRest APIサンプル
登録、更新、編集、削除、参照が可能


### エンドポイント

|エンドポイント|用途|
|---|---|
|POST /v1/members/{member_id}|ユーザー情報の登録|
|GET /v1/members/{member_id}|ユーザー情報の参照|
|PUT /v1/members/{member_id}|ユーザー情報の更新|
|DELETE /v1/members/{member_id}|ユーザー情報の削除|
|GET /v1/members/{member_id}/orders|ユーザー購入情報の参照【全件】|
|GET /v1/members/{member_id}/orders/{order_id}|ユーザー購入情報の参照【購入ID指定】|

### パラメーター

```
POST /v1/members/
```
|キー名|説明|例|必須|
|---|---|---|---|
|email|メールアドレス|hoge@huga.com|○|
|name|名前|yamada-tarou|○|
|sex|性別(maleもしくはfemale)|male|○|


```
GET /v1/members/{member_id}
```

|キー名|説明|例|必須|
|---|---|---|---|
|member_id|メンバーID|1|○|

```
PUT /v1/members/{member_id}
```

|キー名|説明|例|必須|
|---|---|---|---|
|email|メールアドレス|hoge@huga.com|○|
|name|名前|yamada-tarou|○|
|sex|性別(maleもしくはfemale)|male|○|

```
DELETE /v1/members/{member_id}
```

|キー名|説明|例|必須|
|---|---|---|---|
|member_id|メンバーID|1|○|

```
GET /v1/members/{member_id}/orders
```

|キー名|説明|例|必須|
|---|---|---|---|
|member_id|メンバーID|1|○|

```
GET /v1/members/{member_id}/orders/{order_id}
```

|キー名|説明|例|必須|
|---|---|---|---|
|member_id|メンバーID|1|○|
|order_id|購入ID|1|○|


### レスポンス一覧

```
POST /v1/members/{member_id}
```

```
{
  "request_id": "8e96add4-d9b3-11e7-8ed8-525400cae48b",
  "_links": "v1/members/{member_id}",
  "member_id": "{member_id}",
  "email": "hoge@huga.com",
  "name": "yamada-tarou",
  "sex": "male"
}
```

```
PUT /v1/members/{member_id}
```

```
{
  "request_id": "86e8f37e-d9ba-11e7-8ecb-525400cae48b",
  "member_id": "{member_id}",
  "email": "huga@hoge.com",
  "name": "yamada-hanako",
  "sex": "female"
}
```


```
DELETE /v1/members/{member_id}
```

```
レスポンス無し。HTTPステータスコード204のみ。
```

```
GET /v1/members/{member_id}/orders
```

```
{
  "request_id": "9d5f3112-d9bb-11e7-9417-525400cae48b",
  "member_id": "1",
  "orders": [
    {
      "order_id": 1,
      "item_id": "item_1",
      "payment": "card",
      "price": 1000,
      "date": null
    },
    {
      "order_id": 2,
      "item_id": "item_2",
      "payment": "card",
      "price": 1500,
      "date": null
    }
  ]
}
```

```
GET /v1/members/{member_id}/orders/{order_id}
```

```
{
  "request_id": "1ef7a768-d9bc-11e7-934e-525400cae48b",
  "member_id": "1",
  "order_id": 1,
  "item_id": "item_1",
  "payment": "card",
  "price": 1000,
  "date": null
}
```
