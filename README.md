# Omie Image Uploader

![Image Uploader](https://i.imgur.com/67noRO8.jpg)

 A script that dynamically uploads all the images stored in `./src/images` into [Omie](https://app.omie.com.br/) using the [`AlterarProduto` method](https://app.omie.com.br/api/v1/geral/produtos/#AlterarProduto).

 Image directory structure
 ---
 
 ### Folders
 
 In order for Omie's API to get the image, a public URL must be sent referencing it. Therefore, all images must be store inside Github's repository's folder `src/images`.
 
 Each product must have its own foler in `src/images/:product_code`.

 > `:product_code` - Omie's code for the product. Found under "codigo" parameter.
 
 ### Filenames

 Each image filename must be the concatenation between the product's code and a hyphenated sequenced number:
 
 `src/images/:product_code/:product_code:sequential`

 > `:product_code` - Omie's code for the product. Found under `"codigo"` parameter.
 
 >`:sequential` - a hyphen (`'-'`) followed by a sequential number starting in 1.
 
\*Product codes might contain special characters.
 
### Examples

```
src/images/
        PRD01110
            PRD01110-1.jpg
            PRD01110-2.jpg
            PRD01110-3.jpg
            PRD01110-4.jpg
        PRD-00001
            PRD-00001-1.jpg
            PRD-00001-2.jpg
            PRD-00001-3.jpg
            PRD-00001-4.jpg
            PRD-00001-5.jpg
            PRD-00001-6.jpg
        FOO.BAR_123
            FOO.BAR_123-1.jpg
            FOO.BAR_123-2.jpg
```

Limits
---

| |Limit|
|---|:---:|
|Apps|1 per execution|
|Product|Unlimited|
|Images|6 per product|

Omie's API Documentation
---

The endpoint used in this project was [`produtos`](https://app.omie.com.br/api/v1/geral/produtos/) and the methods were the following:

#### [`ListarProdutos`](https://app.omie.com.br/api/v1/geral/produtos/#ListarProdutos)
>###### Function
>- Lists the complete register of all products
>
>###### Parameters
>|Parameter |Description|
>|:---:|:---|
>|[produto_servico_list_request](https://app.omie.com.br/api/v1/geral/produtos/#produto_servico_list_request) |List of registered products |
>
>###### Response
>|Response |Description|
>|---:|:---|
>|[produto_servico_listfull_response](https://app.omie.com.br/api/v1/geral/produtos/#produto_servico_listfull_response) |List of complete registration of products found in Omie|


#### [`ListarProdutosResumido`](https://app.omie.com.br/api/v1/geral/produtos/#ListarProdutosResumido)

>###### Function
>- Lists the basic information of all products
>
>###### Parameters
>|Parameter |Description|
>|:---:|:---|
>|[produto_servico_list_request](https://app.omie.com.br/api/v1/geral/produtos/#produto_servico_list_request) |List of registered products |
>
>###### Response
>|Response |Description|
>|---:|:---|
>|[produto_servico_list_response](https://app.omie.com.br/api/v1/geral/produtos/#produto_servico_list_response) |List of short registration of products found in Omie|


#### [`AlterarProduto`](https://app.omie.com.br/api/v1/geral/produtos/#AlterarProduto)

>###### Function
>- Updates a product
>
>###### Parameters
>|Parameter |Description|
>|:---:|:---|
>|[	produto_servico_cadastro](https://app.omie.com.br/api/v1/geral/produtos/#produto_servico_cadastro) |Complete product's registration |
>
>###### Response
>|Response |Description|
>|---:|:---|
>|[ produto_servico_status](https://app.omie.com.br/api/v1/geral/produtos/#produto_servico_status) |Product registration response status|

---

### Here you can find the [Complete Omie's API documentation](https://app.omie.com.br/).

![[Omie's API documentation](https://app.omie.com.br/developer/service-list/)](https://i.imgur.com/0sGNSsF.png)
