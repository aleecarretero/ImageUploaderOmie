# ImageUploaderOmie
 A script that dynamically uploads all the images stored in `.\src\images` into [Omie](https://app.omie.com.br/) using the [`AlterarProduto` method](https://app.omie.com.br/api/v1/geral/produtos/#AlterarProduto).

 [Omie's API documentation:](https://app.omie.com.br/)
 ![[Omie's API documentation](https://app.omie.com.br/developer/service-list/)](https://i.imgur.com/rbSJQ18.png)

 ### Each image file path must be:
 `src\images\\:product_code`

 > `:product_code` - Omie's code for the product. Found under "codigo" parameter.

 ### Each image file name must be:
 `src\images\\:product_code\\:product_code:sequential`

 > `:product_code` - Omie's code for the product. Found under `"codigo"` parameter.
 
 >`:sequential` - a hyphen (`'-'`) followed by a sequential number between 1 and 6


